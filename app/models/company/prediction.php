<?php
namespace Company;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class Prediction extends DatabaseObject {
	
	protected $fillable = array( 'id', 'comp_id_fk', 'buy_level', 'sell_level', 'accuracy', 'updated', 'host');
	

	protected $table = 'prediction';
	public $timestamps = false; //<-- this will suppress the laravel default timestamb issue

	//declairing the table name as static
	protected static $table_name="prediction";
	protected static $primary_key="id";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('id', 'comp_id_fk', 'buy_level', 'sell_level', 'accuracy', 'updated', 'host');
	
	public $id;
	public $comp_id_fk;
	public $buy_level;
	public $sell_level;
	public $accuracy;
	public $updated;
	public $host;

		
   // find from last saved record to check existence
	public static function update_predicted_data($company_symbol='', $accuracy=1) {
		$curent_data = date("Y-m-d", time());
		$query="SELECT P.* FROM ".self::$table_name. " P 
				INNER JOIN company C ON C.id= P.comp_id_fk 
						AND C.company_symbol= '{$company_symbol}'
							WHERE P.accuracy <= '{$accuracy}' 
							LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_single_record($result_holder) : false;
	}
	   
   	   		
   		// find from last saved record to update existence
	public static function hold_lupdate_predicted_data($company_symbol='', $accuracy=1) {

		$result_holder =DB::table(self::$table_name)
		          ->join('company', function($join) use($company_symbol,$accuracy)
			        {
			            $join->on(self::$table_name.'.comp_id_fk','=','company.id')
			                 ->where('company.company_symbol', '=', $company_symbol)
			                 ->where(self::$table_name.'.accuracy', '<', $accuracy);
			        })->get();

		return  $result_holder ;
	}

   // find from last saved record to check existence
	public static function get_last_saved_record($company_symbol='') {
		$query= "SELECT C.id FROM company C WHERE C.company_symbol= '{$company_symbol}' LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'id') : false;
	}
   
   
   // find the Last top checking results  total
	public static function find_last_top_result_count() {
		$query= "SELECT count(*) as Total FROM ".self::$table_name;
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
	}

   // find the Last top checking results  to list
	public static function find_last_top_result( $per_page, $offset) {
			$morequery=(!empty($per_page) || isset($offset)) ?	"LIMIT {$per_page} OFFSET {$offset}" : '';
			$query="SELECT C.company_name, C.company_symbol, P.* FROM ".self::$table_name. " P 
									INNER JOIN company C ON C.id= P.comp_id_fk ORDER BY P.updated DESC {$morequery} ";

			$result_holder = DB::select( DB::raw( $query ));
			return  $result_holder;
	}
 
   // find the Last top checking results  to list
	public static function find_popular_top_result( $per_page, $offset) {
		 $morequery=(!empty($per_page) || isset($offset)) ?	"LIMIT {$per_page} OFFSET {$offset}" : '';
		 $query="SELECT C.company_name, C.company_symbol FROM ".self::$table_name. " P 
									INNER JOIN company C ON C.id= P.comp_id_fk ORDER BY C.view_count DESC {$morequery} ";
			$result_holder = DB::select( DB::raw( $query ));
			return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;
	}
 
 	public static function find_last_search_FOR_tickers( $per_page=10, $offset=0) {
		$morequery=(!empty($per_page) || isset($offset)) ?	"LIMIT {$per_page} OFFSET {$offset}" : '';
		$query="SELECT GROUP_CONCAT(company_symbol) as ticker FROM(
				SELECT  C.company_symbol FROM ".self::$table_name. " P 
					INNER JOIN company C ON C.id= P.comp_id_fk 
						ORDER BY P.updated DESC {$morequery}
				) as aaa";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'ticker') : false;
	}
	
	// find Prediiction record by company_symbol
	public static function find_by_company_symbol( $company_symbol='') {
		$database = new Database;
		 $query="SELECT P.* FROM ".self::$table_name. " P 
								INNER JOIN company C ON C.id= P.comp_id_fk AND company_symbol='{$company_symbol}' LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_single_record($result_holder) : false;
	}

  
}

?>