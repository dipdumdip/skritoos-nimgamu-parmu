<?php
use \Company\Prediction as Prediction;
use \Company\Company as Company;
use \Company\StockMarket as StockMarket;
use \Company\CompanyData as CompanyData;
use \MyPaginator as Pagination;

class SitemapController extends HomeController {

		/*
			For Viewing full Page data for each company symbols (GET)
		*/	

	public function sitemapCreater()
	{
			echo '<?xml version="1.0" encoding="UTF-8"?> 
					<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';  
				    $articles =Company::Read_data_sitemap();  
				      if($articles){
							foreach($articles as $record){ 
									$company_symbol =$record->company_symbol;
									$company_name =$record->company_name;
									$created =$record->created;
								$displaydate=date("Y-m-d",strtotime($created));
								$url_product = URL::route('home-estimate', array($company_symbol, url_maker($company_name)));
									sitemapper ($url_product, $displaydate);
							}
						}		
			echo  '</urlset>';  	

 				$contents = View::make('sitemap.view_sitemap');
				
				// Create a response and modify a header value
				$response = Response::make($contents, 200)
							->header('Content-Type', 'text/xml');
				return $response;
	}


}
