<?php

# ----  StockPrediction Routes SECTION STARTS HERE-------------------------

		/*
			Test Page index view (GET)
		*/	
Route::get('posts', function()
{
    $posts = Company\Company::all();
 
    return View::make('sitemap.view_sitemap', ['posts', $posts]);
});

		/*
			Home Page index view (GET)
		*/	
	Route::get('/', array(
				'as' => 'home',
				'uses' => 'HomeController@home'
	));


		/*
			For Drawing Graph view Ajax (GET)
		*/	

	Route::get('/get_graph_data', array(
				'as' => 'get-graph-data',
				'uses' => 'HomeController@getGraphData'
	));

		/*
			For Getting new Prediction Ajax (GET)
		*/	

	Route::get('/get_new_prediction', array(
				'as' => 'get-new-prediction',
				'uses' => 'HomeController@getNewPrediction'
	));
		/*
			CSRF Protection group
		*/	
	Route::group(array( 'before' => 'csrf'), function(){
				/*
					For Getting new Prediction Ajax (GET)
				*/	

			// Route::get('/get_new_prediction', array(
			// 			'as' => 'get-new-prediction',
			// 			'uses' => 'HomeController@getNewPrediction'
			// ));

	});
		/*
			For Getting latest Prediction Ajax (GET)
		*/	
	Route::get('/get_last_predictions', array(
				'as' => 'get-last-predictions',
				'uses' => 'HomeController@getLastPredictions'
	));

		/*
			For Getting Updated stockmarket data Ajax (GET)
		*/	
	Route::get('/get_stockmarket_data', array(
				'as' => 'get-stockmarket-data',
				'uses' => 'HomeController@getStockmarketData'
	));
	
		/*
			For Viewing full Page data for each company symbols (GET)
		*/	
	Route::get('/estimation/{code}/{title}.html', array(
				'as' => 'home-estimate',
				'uses' => 'EstimationController@estimateReport'
	));

		/*
			For 404 error page (GET)
		*/	

	Route::get('/404.html', array(
				'as' => '404',
				'uses' => 'ErrorController@show404'
							));
		/*
			For sitemap creation page (GET)
		*/	

	Route::get('/sitemap.xml', array(
				'as' => 'sitemap',
				'uses' => 'SitemapController@sitemapCreater'
				));



