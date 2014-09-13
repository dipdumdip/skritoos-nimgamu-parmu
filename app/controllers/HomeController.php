<?php
use \Company\Prediction as Prediction;
use \Company\Company as Company;
use \Company\StockMarket as StockMarket;
use \Company\CompanyData as CompanyData;
use \MyPaginator as Pagination;

class HomeController extends BaseController {


		/*
			Home Page index view with (GET)
		*/	
	public function home()
	{
		$meta_data= array(
					'title' => 'Home Page :: StockLength.com Free Online Tool To Check Stock Market Future.',
					'metaKeywords' => 'Free, Online Predict, Online Tool, Prediction,  stock market, Predict Stock Market, Live Prediction Stock Market, free advice, when to invest, when to buy stocks, stock game, check to invest, Stock, Market, Stock Market, Tool, Predict Stock Market Software, Best-Charts.',
					'metaDescription' => 'Free online Prediction Stock Market Informer, Online free tool to check stockmarket future,  Online free selling buyer sujection, Featured Predict Stock Market, free fast Online Check and reviews. Latest updates on everything Predict Stock Market Software related.',
					'fb_title' =>'Home Page :: StockLength.com Free Online Tool To Check Stock Market Future.',
					 'fb_image' => return_image('default'),
					'fb_url' => URL::route('home'), 
					// 'company_symbol' => $precision 
				);
			$company_symbol = '';
			$precision = 5;
				return View::make('home', array(
												'meta_data' => $meta_data,
												'tickers' => $this->getTicker(),
												'records' => $this->latestPredictions(),
												'populars' => $this->popularPredictions(),
												'precision' => $precision,
												'company_symbol' => $company_symbol,
												'page_checker' => true //<--this variable used to diffentiate thepages
												 )
									);
	}

		/*
			For Drawing Graph view Ajax (GET)
		*/	

	public function getGraphData()
	{
		$company = Input::get('company');
		$interval= Input::get('interval');
		$precision= Input::get('prc');

		$company_symbol = isset($company) && !empty($company) ? $company : 'goog';
		$interval = isset($interval) && !empty($interval) ? $interval : 'd';
		$precision = isset($precision) && !empty($precision) && is_numeric($precision) ? $precision : 1;
		
		$stock_market =new StockMarket($company_symbol, $interval, $precision);		//<---class declared here and passed the datas
								/* // <--utilizing the function for finding the Company name from Symbol
									//<-- to check the given company symbol is correct or not */
		$company_name =  $stock_market->find_company_name_from_symbol(); 
		if($company_name){			//<-- if company name given is not false go further
			$stock_records=  $stock_market->get_the_market_data();		//<--function to collect Historical data for given company symbol
			if($stock_records){	//<----if it retuns a record then proceed

				$range_from = $stock_market->get_graph_range_start(); //<--this function helps to calculate historical Graph area minmum range
				$range_to = $stock_market->get_graph_range_ends();		//<--this function helps to calculate historical Graph area maximum range

				$lower_range_coords = $stock_market->get_lower_price_data(); 	//<--this function helps to return the lower price data for graph
				$higher_range_coords = $stock_market->get_higher_price_data(); //<--this function helps  to return the higher price data for graph

				$graph = new PHPGraphLib(470,270);
				$graph->addData($lower_range_coords);
				$graph->addData($higher_range_coords);
				$graph->setRange($range_from, 	$range_to);
				$graph->setTitle($company_name.'  Graph');
				$graph->setBars(false);
				$graph->setLine(true);
				$graph->setDataPoints(true);
				$graph->setDataPointColor('maroon');
				$graph->setDataPointColor('red');
				$graph->setDataValues(true);
				$graph->setDataValueColor('blue');
				$graph->setGoalLine(.25);
				$graph->setGoalLineColor('red');
				$graph->createGraph();			//<--this draw the graph
				
				$contents = View::make('graph')->with('graph', $graph);
				
				// Create a response and modify a header value
				$response = Response::make($contents, 200);
				$response->header('Content-Type', 'image/png');
				return $response;

			}//<-- stock data checks ends	
		} //<--company name check ends
	}

		/*
			For Getting latest Prediction Ajax (GET)
		*/	
	public function getLastPredictions()
	{
		return View::make('stockdeals.lastpredictions', array(
												'records' => $this->latestPredictions(),
												
												 )
									);
	}

		/*
			For Getting Updated stockmarket data Ajax (GET)
		*/	
	public function getStockmarketData()
	 {
			$company = Input::get('company');
			$precision= Input::get('prc');

			$company_symbol = isset($company) && !empty($company) ? $company : 'goog';
			$interval = 'd';

			if($company_symbol){
				$stock_market =new StockMarket($company_symbol, $interval, $precision);		//<---class declared here and passed the datas
				$company_name =  $stock_market->find_company_name_from_symbol();
				if($company_name){			//<-- if company name given is not false go further
					$stock_records=  $stock_market->get_the_market_data();		//<--function to collect Historical data for given company symbol
					if($stock_records){	//<----if it retuns a record then proceed
							 echo URL::route('home-estimate', array($company_symbol, $company_symbol));
						}	//<--ends of loop
					}//<-- stock data cheks ends
			}
	 }

	/*
	 	Private function used to load Latest preditions
	*/	

		/*
		 	Private function used to load poular preditions
		*/	

	private function popularPredictions()
	{
		$page = Input::get('start');
		$total= Input::get('total');
		$page= !empty( $page ) ? $page : 1;
		$per_page= 5;
		$total_count= (isset($total))? $total : Prediction::find_last_top_result_count(); 
		//pagination class declaration
		$pagination = new Pagination($page, $per_page, $total_count);
		//main search function calling
		$records = isset($pagination) ? Prediction::find_popular_top_result($per_page, $pagination->offset() ) : false;

		return $records;
	}

		/*
			For Getting Latest stockmarket data Ajax (GET)
		*/	
	public static function latestPredictions()
	{
		$page = Input::get('start');
		$total= Input::get('total');
		$page= !empty( $page ) ? $page : 1;
		$per_page= 5;
		$total_count= (isset($total))? $total : Prediction::find_last_top_result_count(); 
		//pagination class declaration
		$pagination = new Pagination($page, $per_page, $total_count);
		//main search function calling
		$records = isset($pagination) ? Prediction::find_last_top_result($per_page, $pagination->offset() ) : false;

		return $records;
	}

	
		/*
		 	Private function used to generate ticker 
		*/	

	public static function getTicker()
	{

		$stocks = "goog,yhoo,idt,iye,mill,pwer,spy,f,msft,x,sbux,sne,ge,dow,t,ge";
		$stocks_db = Prediction::find_last_search_FOR_tickers();
		$stocks = $stocks_db ? $stocks_db : $stocks;

		$grabbed_record = array();
			$i=-1;
			foreach ( explode(",", $stocks) as $stock ) {
				$i++;
				// Where the stock quote info file should be...
				$local_file =storage_path().DS.'cache'.DS.$stock.".csv";

				
				if (!file_exists($local_file) || filemtime($local_file) <= (time() - (60*60*24))) {	//<-- Else,If it's out-of-date by 15 mins (900 seconds) or more, update it.
					upsfile($stock); 	//<--functiom calls to update remote file to local
				}
				// Open the file, load our values into an array...
					$local_file = fopen ($local_file,"r");
					$stock_info = fgetcsv ($local_file, 1000, ",");

					$font_color = ($stock_info[2]>=0) ? "color: #009900;" :  "color: #ff0000;" ;
					$sign = ($stock_info[2]>=0) ? "&uarr;" :  "&darr;" ;

					$grabbed_record[$i]['symbol']= $stock_info[0];
					$grabbed_record[$i]['font_color']= $font_color;
					$grabbed_record[$i]['direction']= $sign;
					$grabbed_record[$i]['curr_rate']= sprintf("%.2f",$stock_info[1]);
					$grabbed_record[$i]['chng_rate']= sprintf("%.2f",abs($stock_info[2]));
				
				fclose($local_file); 	//<--closes  each opened files
			}
		return $grabbed_record;
	}

		/*
			For 404 error page (GET)
		*/	
	public function show404()
	{ 
		 $meta_data= array('title' => '404 Page Not Found',			//<---easy way to change title storing
							'called_page'=> " ",
							'metaKeywords'=> " ",
							'fb_image' => " ",
							'metaDescription' => " " 
							);		//  which will then retrieved by layout
			return View::make('404', array(		'meta_data' => $meta_data,
												'company_symbol' => '',
												'page_checker' => false //<--this variable used to diffentiate thepages
												 )
								);
	}

}
