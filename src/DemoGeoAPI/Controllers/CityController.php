<?php
/**
 * City controller for all city actions.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use DemoGeoAPI\Application;
use DemoGeoAPI\Service;
use \Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController {

	/**
	 * Return a listing of all cities in a given state
	 *
	 * @param mixed $state
	 * @return mixed
	 */
	public function getCitiesByState($state){
		// Instantiate CityDataService
		$CityDataService = new Service\CityDataService($this->_App['db']);

		// Determine state type
		$stateType = Service\StateDataService::getStateType($state);

		// Lookup cities
		$returnArray = $CityDataService->fetchByState($state, $stateType);
		
		if($this->_format === 'json'){
			return json_encode($returnArray);
		} else {
			return $returnArray;
		}
	}

	/**
	 * Return a listing of all cities within a radius determined by the passed distance
	 *
	 * @param mixed $city
	 * @param mixed $state
	 * @param number $radius -- miles
	 * @throws \Exception
	 * @return mixed
	 */
	public function getCitiesByRadius($city, $state, $radius){
		
		// Validate City/State combo first
		$cityArray = $this->_fetchCity($city, $state);
		if(empty($cityArray)){
			throw new \Exception("Invalid request. Unable to find city/state combo: ".$city."/".$state);
		}

		// Instantiate CityDataService
		$CityDataService = new Service\CityDataService($this->_App['db']);

		// Get Results
		$returnArray = $CityDataService->fetchByCityRadius($cityArray, $radius);

		if($this->_format === 'json'){
			return json_encode($returnArray);
		} else {
			return $returnArray;
		}
	}
}