<?php
/**
 * City controller for all city actions.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class CityController extends AbstractController {
	
	/**
	 * Return a listing of all cities in a given state
	 *
	 * @return mixed
	 */
	public function fetchCitiesByState($state){
		$returnArray = array();

		$returnArray = ['city1','city2','city3'];

		if($this->_format === 'json'){
			return json_encode($returnArray);
		} else {
			return $returnArray;
		}
	}

	/**
	 * Return a listing of all cities within a radius determined by the passed distance
	 *
	 * @return mixed
	 */
	public function fetchCitiesByRadius($state, $city, $radius){
		$returnArray = array();

		$returnArray = ['city4','city5','city6'];

		if($this->_format === 'json'){
			return json_encode($returnArray);
		} else {
			return $returnArray;
		}
	}
}