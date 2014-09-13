<?php
use \Company\Prediction as Prediction;
use \Company\Company as Company;
use \Company\StockMarket as StockMarket;
use \Company\CompanyData as CompanyData;
use \MyPaginator as Pagination;

class EstimationController extends HomeController {

		/*
			For Viewing full Page data for each company symbols (GET)
		*/	

	public function estimateReport($company_symbol, $second_title)
	{
		$second_title_found=Company::find_company_name_comp_id($company_symbol);
		if($second_title_found){
			if(!$second_title || $second_title==$company_symbol){
			return Redirect::route('home-estimate', array($company_symbol, url_maker($second_title_found)));
				}
		}else{
			return Redirect::route('404');
		}
		$interval = Input::get('interval');
		$total= Input::get('total');

			Company::update_chapter_view($company_symbol); //<---this updates the view count of chapter
			$previous_data=CompanyData::Check_recod_exists_by_company_symbol($company_symbol);
			$interval = isset($interval) && !empty($interval) ? $interval : 'd';
			$precision = ($previous_data) ? $previous_data : 1;
			
			$stock_market =new StockMarket($company_symbol, $interval, $precision);		//<---class declared here and passed the datas
			$company_name =  $stock_market->find_company_name_from_symbol();
			
			if($company_name){			//<-- if company name given is not false go further
					$stock_records=  $stock_market->get_the_market_data();		//<--function to collect Historical data for given company symbol
			}
			
			if(!$stock_records){
					return Redirect::route('404');
			}else{

			$buy_level = $stock_market->get_prediction_buy_level();	//<--this function helps to predict the buying level mark
			$sell_level = $stock_market->get_prediction_sell_level();		//<--this function helps to predict the selling level mark
			$compan_details = $stock_market->get_company_description();	//<--get the stored company Descriptions
			$prediction = Prediction::update_predicted_data($company_symbol, $precision);	//<--class declaration/ find to update for prediction 
				if($prediction){
						$company_id=Company::find_company_id_company_symbol($company_symbol);
						$ipLite = new ip2locationlite;
						$ipLite->setKey('516921f89d86829aafa20f18ff7408fe4f2e974d114b53593f56a91ab7a286cc');
						$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
						$ge_countryName = (!empty($locations) && is_array($locations)) ? $locations['cityName'] : "undeclared" ;
				$prediction = Prediction::where('comp_id_fk', '=', $company_id)->delete();
							$company = Prediction::create( array(
									'comp_id_fk' =>$company_id,
									'buy_level' =>trim($buy_level),
									'sell_level' =>trim($sell_level),
									'accuracy' => $precision,
									'updated' => date("Y-m-d H:i:s", time()),
									'host' => $ge_countryName
									));	
					}
			}
		
		 $meta_data= array('title' => ucwords($company_name)." Stock Market Value Prediction Result :: StockLength.com To Check Your Risk Level On Stock Market",			//<---easy way to change title storing
							'metaDescription' => ucwords($company_name). ' find online Prediction Stock Market Informer, Online free tool to check stockmarket future '.ucwords($company_name).',  Online free selling buyer sujection, Featured Predict Stock Market, free fast Online Check and reviews '.ucwords($company_name).'. Latest updates on everything Predict Stock Market related.',
							'company_symbol' => $company_symbol,
							'precision' => $precision,
							'fb_image' => return_image($company_symbol.'.jpeg'),
				);

				return View::make('estimate', array(
												'meta_data' => $meta_data,
												'company_name' => $company_name,
												'compan_details' => $compan_details,
												'stock_records' => $stock_records,
												'stock_market' => $stock_market,
												'buy_level' => $buy_level,
												'sell_level' => $sell_level,
												'precision' => $precision,
												'records' => parent::latestPredictions(),
												'tickers' => parent::getTicker(),
												'company_symbol' => $company_symbol,
												'page_checker' => true //<--this variable used to diffentiate thepages
												 )
									);
	}


}
