<?php
namespace Wd\Ac\Traits;

trait Form_Error{

	/**
	* Check error
	*
	* @return boolean  
	*/
	function has_error($key){
		return isset($this->errors[$key]) ? true : false;
	}
	/**
	* Get error  
	*
	* @param $key 
	*
	* @return boolean  
	*/

	function get_error($key){
		if( isset($this->errors[$key])){
			return $this->errors[$key];
		}
		return false;
	}

}