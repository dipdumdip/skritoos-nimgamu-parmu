<?php
namespace Company;

 class StockMarket { 
	
	//list the essential class variables 
		public $company_symbol;
		public $interval;
		public $precision;
	
	//declaring the  file handling varirbels
		protected $check_file_handle;
	
	//declaring the future prediction dependent variable
		protected $low_data_array=array();
		protected $high_data_array=array();
		protected $volume_array=array();

	// declaration for holding result data
		protected $company_name;
		protected $company_description;
		protected $company_address;
		protected $main_record;
		
	// declaring the predicion data varible
		protected $buyers_level;
		protected $seller_level;
	
	function __construct($company_symbol="", $interval="d", $precision="7") { //<--construct function
						//<---assign the initial values
		$this->company_symbol=$company_symbol;
		$this->interval=$interval;
		$this->precision=$precision;
			//<--intial function calls
		$this->allot_company_name_from_symbol();	//<--this function helps to find the company and assing to the class variable
		$this->allot_the_market_data();		//<--this function helps to collect and allot actual Stock market data
	}

	public function get_precision_value() {	//<--this function helps to calculate allowed precision value
		$precision_multi = $this->precision>=0  && $this->precision<=10 ? PRECISION*$this->precision : PRECISION;
		return $precision_multi;
	}	
	
	public function get_date_period_start() {	//<--this function helps to calculate historical Period Starts
	 $start_date= date('Y-m-d', (time()-$this->get_precision_value()));
	 return !empty($start_date) ? explode('-',  $start_date) : array();
	}	
	
	public function get_date_period_ends() {	//<--this function helps to calculate historical Period Ends
	   $end_date= date('Y-m-d', time());
	 return !empty($end_date) ? explode('-',  $end_date) : array();
	}	
	
	public function allot_company_name_from_symbol() {	//<--this function helps to find the company and assing to the class variable
		$Check_file = 'http://finance.yahoo.com/d/quotes.csv?s='.$this->company_symbol.'&f=sn';
		$this->check_file_handle = fopen($Check_file, "r");
		$check_record = fgetcsv($this->check_file_handle, false, ',');	//<----exploring CSV file
		$this->company_name= $check_record[1] != strtoupper($this->company_symbol) ? $check_record[1] : false;
	}	
		
	public function find_company_name_from_symbol() {	//<--this function helps to find and return company acual name from Symbol
		return !empty($this->company_name) ? $this->company_name : false;
	}	
			
	public function Get_record_file_handle() {	//<--this function helps to find and return clculating records data
		$csvStored_data="";
			$start_date=$this->get_date_period_start();
			$end_date=$this->get_date_period_ends();
				$month_start = $start_date[1]-1;	//<--special arrangement for yahoo finance data (0 -> january)
				$month_ends = $end_date[1]-1;	//<--special arrangement for yahoo finance data (0 -> january)
				 $file = 'http://ichart.yahoo.com/table.csv?s='.$this->company_symbol.'&a='.$month_start.'&b='.$start_date[2].'&c='.$start_date[0]
																	.'&d='.$month_ends.'&e='.$end_date[2].'&f='.$end_date[0].'&g='.$this->interval.'&ignore=.csv';
			$stored_data=CompanyData::get_last_updated_record($this->company_symbol);	//<-- this function finds the data from database
				if($stored_data){
					if( (date("Y-m-d", strtotime($stored_data->updated))<date("Y-m-d", time())) || ($stored_data->accuracy < $this->precision)){
						$csvStored_data = csv_to_array($file, ','); 	//<----exploring CSV file
					  if(count($csvStored_data)>4){ //<--checks for the genuinity
						$company_id=Company::find_company_id_company_symbol($this->company_symbol);

						$prediction = CompanyData::where('comp_id_fk', '=', $company_id)->delete();
							$company = CompanyData::create( array(
									'comp_id_fk' =>$company_id,
									'csvdata' =>serialize($csvStored_data), 	//<----exploring CSV file
									'updated' => date("Y-m-d H:i:s", time()),
									'accuracy' => $this->precision
									));	
					  }
					}else{
							$csvStored_data = unserialize($stored_data->csvdata);
					 } 
					 $this->company_description=$stored_data->description;
					 $this->company_address=$stored_data->address;
				}else{
					$company = new Company();
						$company->company_name=trim($this->company_name);
						$this->company_description=get_Company_data($this->company_symbol, 'desci');
						get_Company_logo($this->company_symbol); //<--fuction used to copy image 
						$company->description=$this->company_description;
						$company->company_symbol=trim($this->company_symbol);
						$csvStored_data = csv_to_array($file, ','); 	//<----exploring CSV file
				   if(count($csvStored_data)>4){ //<--checks for the genuinity
							$company = Company::create( array(
									'company_name' =>trim($this->company_name),
									'company_description' => get_Company_data($this->company_symbol, 'desci'),
									'description' => $this->company_description,
									'company_symbol' => trim($this->company_symbol)
									));
						if($company){
								if($csvStored_data){
									get_Company_logo($this->company_symbol); //<--fuction used to copy image 
								}
						$CompanyData = CompanyData::create( array(
								'comp_id_fk' =>trim($company->id),
								'csvdata' => serialize($csvStored_data),
								'updated' => date("Y-m-d H:i:s", time()),
								'accuracy' => $this->precision
								));
						$predict_data = Prediction::create( array(
								'comp_id_fk' =>trim($company->id)
								));	
						}
					}
				}

		return !empty($csvStored_data) ? $csvStored_data : false;
	}	
	
	public function allot_the_market_data() {		//<--this function helps to collect and allot actual Stock market data
		$record =array(); 	
		if(!empty($this->company_name)){	//<---company details error checks
					$this->main_record=$this->Get_record_file_handle();
				if($this->main_record){
					foreach ($this->main_record as $row){	//<--loop throught the values
							$this->low_data_array[date("Md",strtotime($row['Date']))] = $row['Low'];
							$this->high_data_array[date("Md",strtotime($row['Date']))] = $row['High'];
							$this->volume_array[date("Md",strtotime($row['Date']))] = $row['Volume'];
					}
				}else{
					Company::delete_record_data_by_company_symbol($this->company_symbol);
					return false;
				}
		}	//<---company details error checks end
	}	
		
	public function get_the_market_data() {		//<--this function helps to return actual Stock market data 
		return !empty($this->main_record) ? $this->main_record : false;
	}	
	
	public function get_lower_price_data() {	//<--this function helps to return the lower price data for graph
									//<---this keeps the maximum first 30 array elements to plot graph
			$holding_array = count($this->low_data_array)>7 ? array_slice($this->low_data_array, 0, 7) : $this->low_data_array;
		return count($holding_array)>1 ? array_reverse($holding_array) : array();
	}	
	
	public function get_higher_price_data() {	//<--this function helps  to return the higher price data for graph
									//<---this keeps the maximum first 30 array elements to plot graph
			$holding_array = count($this->high_data_array)>7 ? array_slice($this->high_data_array, 0, 7) : $this->high_data_array;
		return count($holding_array)>1 ? array_reverse($holding_array) : array();
	}		
	
	public function get_graph_range_start() {	//<--this function helps to calculate historical Graph area minimum range
			$array_lower=$this->get_lower_price_data();	//<--gets the graph array details
		return count($array_lower)>1 ? min($array_lower) : 0;
	}	
	
	public function get_graph_range_ends() {	//<--this function helps to calculate historical Graph area maximum range
			$array_higher=$this->get_higher_price_data();	//<--gets the graph array details
		return count($array_higher)>1 ? max($array_higher) : 0;
	}	
	
	public function get_prediction_buy_level() {		//<--this function helps to predict the buying level mark
		return count($this->low_data_array)>1 ? min($this->low_data_array) : 0;
	}	
	
	public function get_prediction_sell_level() {		//<--this function helps to predict the selling level mark
		return count($this->high_data_array)>1 ? max($this->high_data_array) : 0;
	}	
	
	public function get_company_description() {		//<--this function helps to predict the selling level mark
		return !empty($this->company_description) ? ($this->company_description) : '';
	}	
	
	function __destruct() {		//<--	destruct function 
		fclose($this->check_file_handle);	//<-- opened file close for checking company record  
	}
  
}

?>