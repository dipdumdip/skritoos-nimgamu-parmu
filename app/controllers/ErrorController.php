<?php

class ErrorController extends HomeController {

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
