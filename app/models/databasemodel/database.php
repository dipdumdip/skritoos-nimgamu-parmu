<?php
namespace Models\Database;
// use Models\Database\TransPDO AS PDO;
use PDO;
use Memcache;
use \Zend\Cache As Zend_Cache;
use \Zend\Registry AS Zend_Registry;
use Zend_Db_Table_Abstract;

// All basic MYSQL functions were declaired here in this class to hold it very tight...

class database {
	
	protected $connection;
	protected $cache;
	protected $zend_cache;
	public $last_query;
	
	private $error;
	private $stmt;
	protected $transactionCounter = 0; 

	private $magic_quotes_active;
	private $real_escape_string_exists;

	 function __construct() {
		$this->open_connection();
			$this->magic_quotes_active = get_magic_quotes_gpc();
			$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );
	
		// $this->cache = new Memcache();
		// $this->cache->connect(CACHE_SERVER, CACHE_PORT) or die ("Could not connect");
 
		$this->zend_cache = Zend_Cache::factory( 'Core', 'File',
											array(
												'lifetime' => 3600 * 24, //cache is cleaned once a day
												'automatic_serialization' => true
											), array('cache_dir' => SITE_ROOT.'/cache_files')
								);
				    Zend_Registry::set('cached', $this->zend_cache);	//<---set a registry refference
			$this->zend_cache = Zend_Registry::get('cached');	//<---gets the a registry refference

	 }

	public function open_connection() {
		 $options = array(
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					);
			try {
					$this->connection  = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USERNAME, DB_PASSWORD,  $options);
					/*** echo a message saying we have connected ***/
					// echo 'Connected to database<br />';

				}
				catch(PDOException $e){
					echo $e->getMessage();
						error_log($e->getMessage()."\r\n", 3, $error_Log_file);
					}

		}

	public function close_connection() {
		if(isset($this->connection)) {
			$this->connection= null;
			unset($this->connection);
		}
	}

	public function query($sql, $query_bind_array=NULL) {
		$this->last_query = $sql; $result=false;

			try {
				$this->stmt =  $this->connection->prepare($sql);

			$this->execute($query_bind_array);
			$result = $this->stmt;
			} catch(PDOException $e) {
					$result=false;
					echo $e->getMessage();
					error_log($e->getMessage()."\r\n", 3, $error_Log_file);
					die();
			}	
	
		return $result;
	}

 	public function multi_query($query) {
				 $this->query_Common($query);
				 $this->execute();
		}
		
	public function query_Common($query) {
		$this->stmt = $this->connection->prepare($query);
		return $this->stmt;
	}
	 
	public function execute($query_pass=NULL) {
		try {
			return $this->stmt->execute($query_pass);
		} catch(PDOException $e) {
					echo $e->getMessage();
					error_log($e->getMessage()."\r\n", 3, $error_Log_file);
					die();
		}	
	}

	public function errorCode() {
            return $this->connection->errorCode();
    }

	

  public function session_impo_dataProcessing($query_pass='', $params_array=null) {		//<---which is equalent to fetch object
		// $key_name = 'querycache-' . md5(serialize(array($query_pass, $params_array)));	//<----cache key name creation...
		// $get_result = $this->cache->get($key_name);// <----cache key name existancy Checking...
			// if ($get_result) {		//<---use the cached result if found one.... 
					// $result=$get_result;
			// }else{
				$this->stmt = $this->connection->prepare($query_pass);
				$this->stmt->execute($params_array);
				$result=$this->stmt->fetch(PDO::FETCH_ASSOC);
				// $this->cache->set($key_name, $result, false, 10); // Store the result of the query for 20 seconds
			// }
		return $result;
	}

 	public function fetch_array_cached($query_pass='', $params_array=null) {		//<---which is equalent to fetch object
		$result='';
		$key_name = 'querycache_' . md5(serialize(array($query_pass, $params_array)));	//<----cache key name creation...
		// $get_result = $this->cache->get($key_name);// <----cache key name existancy Checking...
		
			$get_result = $this->zend_cache->load($key_name);// <----cache key name existancy Checking...
			    $registry = Zend_Registry::getInstance();
     

			// print_r(Zend_Registry::getInstance());
			if ($get_result) {		//<---use the cached result if found one.... 
					$result=$get_result;
					// echo 'cached';
			}else{
				$this->stmt = $this->connection->prepare($query_pass);
				$this->stmt->execute($params_array);
				while($row=$this->stmt->fetch()){
						$result[]=$row;
					}
					$this->zend_cache->save($result, $key_name);
				// $this->cache->set($key_name, $result, false, CACHE_TIMING);	 // Store the result of the query for 20 seconds
			}
		return $result;
	}
	
	public function fetch_object_cached($query_pass='', $params_array=null) {		//<---which is equalent to fetch object
		$result='';
		$key_name = 'querycache_' . md5(serialize(array($query_pass, $params_array)));	//<----cache key name creation...
		// $get_result = $this->cache->get($key_name);// <----cache key name existancy Checking...
				$get_result = $this->zend_cache->load($key_name);// <----cache key name existancy Checking...
			    $registry = Zend_Registry::getInstance();
	if ($get_result) {		//<---use the cached result if found one.... 
					$result=$get_result;
			}else{
				$this->stmt = $this->connection->prepare($query_pass);
				$this->stmt->execute($params_array);
				while($row=$this->stmt->fetch(PDO::FETCH_OBJ)){
						$result[]=$row;
					}
					$this->zend_cache->save($result, $key_name);
				// $this->cache->set($key_name, $result, false, CACHE_TIMING);	 // Store the result of the query for 20 seconds
			}
		return $result;
	}
	
 	public function fetch_assoc_cached($query_pass='', $params_array=null) {		//<---which is equalent to fetch object
		$key_name = 'querycache_' . md5(serialize(array($query_pass, $params_array)));	//<----cache key name creation...
		// $get_result = $this->cache->get($key_name);// <----cache key name existancy Checking...
				$get_result = $this->zend_cache->load($key_name);// <----cache key name existancy Checking...
			    $registry = Zend_Registry::getInstance();
			if ($get_result) {		//<---use the cached result if found one.... 
					$result=$get_result;
			}else{
				$this->stmt = $this->connection->prepare($query_pass);
				$this->stmt->execute($params_array);
				$result=$this->stmt->fetch(PDO::FETCH_ASSOC);
					$this->zend_cache->save($result, $key_name);
				// $this->cache->set($key_name, $result, false, CACHE_TIMING);	 // Store the result of the query for 20 seconds
			}
		return $result;
	}

	// "database-neutral" methods
	public function fetch_assoc($result_set) {
		return $result_set->fetch(PDO::FETCH_ASSOC);
	}

	public function fetch_array($result_set) {
		return $result_set->fetch();
	}

	public function fetch_object($result_set) {
		// return $result_set->fetch_object();
		return $result_set->fetch(PDO::FETCH_OBJ);
	}

	public function num_rows($result_set) {
		return $result_set->fetch(PDO::FETCH_NUM);
	}

	public function insert_id() {
		// get the last id inserted over the current db connection
		return $this->connection->lastInsertId();
	}

	public function connection() {
		// get the last id inserted over the current db connection
		return $this->connection;
	}

	public function cached() {
		// get the last id inserted over the current db connection
		// return $this->cache;
		return $this->zend_cache;
	}

	public function affected_rows() {
		 // get the effected raws total 
		return $this->stmt->rowCount();
	}

	public function escape_value( $value ) {
		// if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			// if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			// $value = mysql_real_escape_string( $value );
		// } else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			// if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		// }
		// return strip_tags($value, "<h1><h2><h3><div><span><b><p>");
		return $value;
	}
	
	private function confirm_query($e) {
		$output = "Database query failed: " .  $e->getMessage() . "<br /><br />";
		$output .= "Last SQL query: " . $this->last_query;
		die( $output );
	}
	
	public function get_last_query() {
		return $this->last_query;
	}
	
}

	  $database= new \models\database\Database();
$db =& $database;

?>