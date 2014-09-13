<?php
	function get_html_data($url) {
	  $ch = curl_init();
	  $timeout = 5;
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data; 
	}
	
	function get_Company_data($company_symbol='', $altitude='desci') {
		$curl = curl_init('http://investing.money.msn.com/investments/company-report?symbol='.$company_symbol);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			$page = curl_exec($curl);
			if(curl_errno($curl)){
				return false;
				exit;
			}
			curl_close($curl);
			$regex = $altitude=='desci' ? '/<tr  class="summary first">(.*?)<\/tr>/s' : '/<div class="attribution">(.*?)<\/div>/s';
			if ( preg_match($regex, $page, $list) ){
				return  trim(strip_tags($list[0]));
			}else{
				return false;
			}
	}	
	
	function get_Company_logo($company_symbol='') {
		if (checkRemoteFile ("http://content.nasdaq.com/logos/{$company_symbol}.gif")){
		copy("http://content.nasdaq.com/logos/{$company_symbol}.gif", public_path()."/uploads/{$company_symbol}.jpeg");
		}
	}
	
	function upsfile($stock) {	//<--- Function to copy a stock quote CSV from Yahoo to the local cache. CSV contains symbol, price, and change
		if (checkRemoteFile ("http://finance.yahoo.com/d/quotes.csv?s={$stock}&f=sl1c1&e=.csv")){
			copy("http://finance.yahoo.com/d/quotes.csv?s=$stock&f=sl1c1&e=.csv", storage_path().DS.'cache'.DS.$stock.".csv"); 
		}
	}
	
	function checkRemoteFile($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(curl_exec($ch)!==FALSE){
			return true;
		}else{
			return false;
		}
	}

	function url_maker($your_string){		//<---this function is used to create nice/safe URL
			// return rawurlencode($your_string);
				$str= preg_replace('/\s+/', '_', $your_string);
				$str=  str_replace('&', 'and', $str);
				$str=  str_replace('%', 'percent', $str);
				$str=  str_replace('/', 'by', $str);
				$str=  str_replace('-', '_', $str);
				$str=  str_replace("'", '', $str);
				$str=  str_replace(".", '_', $str);
				$str=  str_replace('"', '', $str);
				$str=  str_replace('‘', '', $str);
				$str=  str_replace('’', '', $str);
				$str=  str_replace('^', '', $str);
				$str=  str_replace('$', '', $str);
				$str=  str_replace('#', '', $str);
				$str=  str_replace('*', '', $str);
				$str=  str_replace(',', '_', $str);
				$str=  str_replace('|', '', $str);
				$str=  str_replace("\\", '', $str);
			   $str=  str_replace('?', '', $str);
			  $string = preg_replace('/_+/', '_', $str);
			return  strtolower(substr($string,0,70));
	}

	function sitemapper ($url_product='', $displaydate=''){	//<---this function helps to create sitemepa
		echo "  
				<url> 
				<loc>".$url_product."</loc>  
				<lastmod>".$displaydate."</lastmod>  
				<changefreq>daily</changefreq>  
				<priority>0.8</priority>  
				</url>  
				";  
	}

				//<----this function find and return the image path alone
	function return_image($img_path){
		if (file_exists (public_path()."/uploads/".$img_path)){
			return URL::to('/')."/uploads/".$img_path;
		}else{
			return URL::to('/')."/uploads/default.jpg";
		}

	}
	
		//<>-----function for Tag display words were shorten here
	function ShortenText($text,$chars=20) {
		// Change to the number of characters you want to display
			if( strlen($text)-$chars>5){
				$text = substr($text,0,($chars+3));
				$text = $text.'...';
			}else if( strlen($text)>=$chars){
				$text = substr($text,0,$chars);
			}
			return $text;
	}	

	function csv_to_array($filename='', $delimiter=','){		//<--this function converts the csv file into array
		$header = NULL;
		$data = array();
		if (checkRemoteFile ($filename)){

			if (($handle = fopen($filename, 'r')) !== FALSE){
				while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
					if(!$header)
						$header = $row;
					else
						$data[] = array_combine($header, $row);
				}
				fclose($handle);
			}
		}
		return $data;
	}

	function startsWith($haystack, $needle){	//<---function to check the string start with given leter
		 return (substr($haystack, 0, strlen($needle)) === $needle);
	}

	function endsWith($haystack, $needle){		//<---function to check the string ends with given leter
		return substr($haystack, -strlen($needle))===$needle;
	}
	
	function simple_encode($string='') {		//<-----this function is used do simple ENCODE
		return base64_encode($string); // or return NULL;
	}
	
	function simple_decode($string='') {		//<-----this function is used do simple DECODE
		return base64_decode($string); // or return NULL;
	}

	function time_ago($time_in){	//<----this function to help creating time ago from given time
		$time_in = is_numeric($time_in) ?  $time_in :  strtotime($time_in) ; 
		$m = time()-$time_in; $o='just now';
		$t = array('year'=>31556926,'month'=>2629744,'week'=>604800,'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
		foreach($t as $u=>$s){
			if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
		}
		return $o;
	}
		
	function redirect_to( $location = NULL){	//<--- function for redirecting locations pages
		  if ($location != NULL) {
					header("Location: {$location}");
					exit;
		  }
	}
	

//#####################################################################################################   ----C0MM0N	
	//<>-----function helps to get hte current IP address of the user
	function getIp(){
        if (getenv('HTTP_CLIENT_IP')) {
            $userIp = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $userIp = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $userIp = getenv('REMOTE_ADDR');
        } else {
            $userIp = '';
        }
        return $userIp;
    }
	
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);

	//<----contant to declare the base document path from here all other paths are related
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);

	//<----contant to declare the library directory  path
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.'includes'.DS);

defined('BASE_DOCUMENT') ? null : define('BASE_DOCUMENT', SITE_ROOT.'public'.DS);

 //Section includes library paths 
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__FILE__).DS."..".DS);
set_include_path(get_include_path().PATH_SEPARATOR.SITE_ROOT.'models');
set_include_path(get_include_path().PATH_SEPARATOR.SITE_ROOT);


	//<----contant to declare the caching directory path 

defined('PRECISION') ? null : define('PRECISION', 86400*10);		//<---default precision declared

?>