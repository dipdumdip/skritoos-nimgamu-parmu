<?php
namespace Company;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class Company extends DatabaseObject {
	
		protected $fillable = array(  'company_symbol', 'company_name',  'address',  'description',   'view_count');
	//declairing the table name as static
	protected $table="company";
	protected static $table_name="company";
	protected static $primary_key="id";
	public $timestamps = false; //<-- this will suppress the laravel default timestamb issue

	//list the essential database fields into an array for CRUD
	

  
	//Function to update the view count of each record by appointment
	public static function update_chapter_view($company_symbol="") {
			$query = "UPDATE ".self::$table_name." SET `view_count` = 
								view_count+1 WHERE company_symbol= '{$company_symbol}' LIMIT 1";
			$result_array = DB::raw( $query );
			return $result_array;
	}

	// function to remove record by id
	public static function delete_record_data($id=0) {
		$query= "DELETE FROM `".static::$table_name."` WHERE id = '{$id}'  LIMIT 1";
		$result_array = DB::raw( $query );
		return $result_array;
	}	

   		// function to remove record by company_symbol
	public static function delete_record_data_by_company_symbol($company_symbol=0) {
		$query= "DELETE FROM `".static::$table_name."` WHERE company_symbol = '{$company_symbol}' ";
		$result_array = DB::raw( $query );
		return $result_array;
	}	
  
   		// function to Read_data_sitemap
	public static function Read_data_sitemap() {
		$query="SELECT company_symbol, company_name, created 
		 			FROM ".self::$table_name. " ORDER BY created DESC ";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;
	}	
   
		// find company_name from given company symbol
	public static function find_company_name_comp_id($company_symbol="") {
		 $query="SELECT company_name FROM ".self::$table_name." WHERE company_symbol ='{$company_symbol}' LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'company_name') : false;
 	}
    
     
		// find company_name from given company symbol
	public static function find_company_id_company_symbol($company_symbol="") {
		 $query="SELECT id FROM ".self::$table_name." WHERE company_symbol ='{$company_symbol}' LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'id') : false;
 	}
    
  
}

?>