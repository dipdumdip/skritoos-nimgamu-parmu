<?php
namespace DatabaseModel;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Eloquent;
use PDO;

class DatabaseObject extends Eloquent implements UserInterface, RemindableInterface {
	use UserTrait, RemindableTrait;
	
	// "database-neutral" methods
	public static function fetch_assoc($result_set, $search='') {
			$result_set=	$result_set[0];
		return isset($result_set->$search) ? $result_set->$search : false;
	}	
	// "database-neutral" methods
	public static function fetch_single_record($result_set) {
			$result_set=	$result_set[0];
		return isset($result_set) ? $result_set : false;
	}

	public static function fetch_array($result_set) {
		return json_decode(json_encode((array) $result_set), true);
	}

	public static function num_rows($result_set) {
		return count($result_set)>0 ? true : false;
	}


	public static function fetch_object($result_set) {
		// return $result_set->fetch_object();
		return $result_set;
	}
}