<?php
/**
 * Visit controller for all visit actions.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use DemoGeoAPI\Application;
use DemoGeoAPI\Service;
use \Symfony\Component\HttpFoundation\Request;

class VisitController extends AbstractController {

	/**
	 * Creates a new set of visit records using the request
	 * Deletes any existing records as part of the process
	 *
	 * @param int $userId
	 * @throws \Exception
	 * @return mixed
	 */
	public function createVisits($userId){
		// Validate user
		$userArray = $this->_fetchUser($userId);
		if(empty($userArray)){
			throw new \Exception("Unable to retrieve user with UserID ".$userId);
		}

		// Delete existing visits record(s)
		$this->_deleteExistingVisits($userId);

		// Attempt to create new record(s) from request
		$this->_createVisitRecords($userId);

		// Retrieve list of all visits recorded
		$visitsArray = $this->_fetchVisitRecords($userId);

		// Return the result
		if($this->_format === 'json'){
			return json_encode($visitsArray);
		} else {
			return $visitsArray;
		}
	}

	/**
	 * Updates the set of visit records using the request
	 * Appends new records to any existing records as part of the process
	 *
	 * @param int $userId
	 * @throws \Exception
	 * @return mixed
	 */
	public function updateVisits($userId){
		// Validate user
		$userArray = $this->_fetchUser($userId);
		if(empty($userArray)){
			throw new \Exception("Unable to retrieve user with UserID ".$userId);
		}

		// Attempt to create new record(s) from request
		$this->_createVisitRecords($userId);

		// Retrieve list of all visits recorded
		$visitsArray = $this->_fetchVisitRecords($userId);

		// Return the result
		if($this->_format === 'json'){
			return json_encode($visitsArray);
		} else {
			return $visitsArray;
		}
	}

	/**
	 * Fetches the set of visit records for the given userId
	 *
	 * @param int $userId
	 * @throws \Exception
	 * @return mixed
	 */
	public function getVisits($userId){
		// Validate user
		$userArray = $this->_fetchUser($userId);
		if(empty($userArray)){
			throw new \Exception("Unable to retrieve user with UserID ".$userId);
		}

		// Retrieve list of all visits recorded
		$visitsArray = $this->_fetchVisitRecords($userId);

		if($this->_format === 'json'){
			return json_encode($visitsArray);
		} else {
			return $visitsArray;
		}
	}

	/**
	 * Delete all user recorded visits
	 * 
	 * @throws \Exception
	 * @return void
	 */
	protected function _deleteExistingVisits($userId){
		// Instantiate UserDataService
		$VisitDataService = new Service\VisitDataService($this->_App['db']);

		// Delete user records
		$VisitDataService->deleteByUserId($userId);
	}

	/**
	 * Create new visit records
	 *
	 * @param int $userId
	 * @throws \Exception
	 * @return void
	 */
	protected function _createVisitRecords($userId){
		$RequestBody = json_decode($this->_Request->getContent());

		// Treat all visit creation requests as an array
		if(!is_array($RequestBody)){
			$RequestBody = array($RequestBody);
		}

		// Instantiate VisitDataService
		$VisitDataService = new Service\VisitDataService($this->_App['db']);

		// Iterate over each visit request
		foreach($RequestBody as $VisitRequest){
			// Validate city on request
			if(!isset($VisitRequest->city) || empty($VisitRequest->city)){
				throw new \Exception("Invalid visit request. Unable to create a visit record without a city.");
			}

			// Validate state on request
			if(!isset($VisitRequest->state) || empty($VisitRequest->state)){
				throw new \Exception("Invalid visit request. Unable to create a visit record without a state.");
			}

			// Attempt to lookup the CityID using the passed info
			$cityArray = $this->_fetchCity($VisitRequest->city, $VisitRequest->state);
			if(empty($cityArray)){
				throw new \Exception("Invalid visit request. Unable to find city/state combo: ".$VisitRequest->city."/".$VisitRequest->state);
			}

			$insertArray = array(
				'UserID'=>$userId,
				'CityID'=>$cityArray['CityID']
			);

			try{
				$VisitDataService->insert($insertArray);
			} catch(\Exception $e){
				throw new \Exception("Failed to insert visit record for UserID ".$userId." with city/state combo ".$VisitRequest->city."/".$VisitRequest->state);
			}
		}
	}

	/**
	 * Fetch the visit records for the given userId
	 *
	 * @param int $userId
	 * @return array
	 */
	protected function _fetchVisitRecords($userId){
		// Instantiate VisitDataService
		$VisitDataService = new Service\VisitDataService($this->_App['db']);

		// Lookup and return visits array
		return $VisitDataService->fetchByUserId($userId);
	}
}