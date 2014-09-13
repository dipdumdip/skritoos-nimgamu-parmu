<?php
namespace Company;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class CompanyData extends DatabaseObject {
	
		protected $fillable = array( 'id', 'comp_id_fk', 'accuracy', 'csvdata', 'updated');
	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'company_data';
	public $timestamps = false; //<-- this will suppress the laravel default timestamb issue

		//declairing the table name as static
	protected static $table_name="company_data";
	
	protected static $primary_key="id";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('id', 'comp_id_fk', 'accuracy', 'csvdata', 'updated');
	
	public $id;
	public $comp_id_fk;
	public $accuracy;
	public $csvdata;
	public $updated;

	
   // find from last saved record to check existence
	public static function get_last_updated_record($company_symbol='') {
		$curent_data = date("Y-m-d", time());
		$query="SELECT CD.*, C.description, C.address FROM ".self::$table_name. " CD 
				INNER JOIN company C WHERE C.id= CD.comp_id_fk 
						AND C.company_symbol= '{$company_symbol}'" 
								// ." WHERE DATE_FORMAT(CD.updated,'%Y-%c-%e')<='{$curent_data}' ".
					." LIMIT 1";
		    // $result_array = static::find_by_sql($query);
		// return !empty($result_array) ? array_shift($result_array) : false;
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_single_record($result_holder) : false;
	}
   
   		
  		 // find from last saved record to check existence
	public static function get_last_data_by_company_symbol($company_symbol='') {
		$curent_data = date("Y-m-d", time());
		$query="SELECT CD.* FROM ".self::$table_name. " CD 
				INNER JOIN company C WHERE C.id= CD.comp_id_fk 
						AND C.company_symbol= '{$company_symbol}'" 
								// ." WHERE DATE_FORMAT(CD.updated,'%Y-%c-%e')<='{$curent_data}' ".
					." LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_single_record($result_holder) : false;
	}
   
   	   		
  		 // find from last saved record to check existence ONLY TO save/Updates
	public static function hold_last_data_by_company_symbol($company_symbol='') {
		$curent_data = date("Y-m-d", time());

		$result_holder =DB::table(self::$table_name)
		          ->join('company', function($join) use($company_symbol)
			        {
			            $join->on(self::$table_name.'.comp_id_fk','=','company.id')
			                 ->where('company.company_symbol', '=', $company_symbol);
			        })->get();

		return  $result_holder ;
	}
   
   	
   // find accuracy from last saved record to check existence
	public static function Check_recod_exists_by_company_symbol($company_symbol='') {
		$curent_data = date("Y-m-d", time());
		$query="SELECT CD.accuracy FROM ".self::$table_name. " CD 
				INNER JOIN company C WHERE C.id= CD.comp_id_fk 
						AND C.company_symbol= '{$company_symbol}'" 
								// ." WHERE DATE_FORMAT(CD.updated,'%Y-%c-%e')<='{$curent_data}' ".
					." LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'accuracy') : false;
	}
   
	   
		// find the Last top checking results  total
	public static function find_last_top_result_count() {
		$query ="SELECT count(*)as Total FROM ".self::$table_name;
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
	}

  
}

?>