<?php
/**
 * User controller for all user actions.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use DemoGeoAPI\Application;
use DemoGeoAPI\Service;
use \Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController {

	/**
	 * Retrieve the user record for a given userId
	 *
	 * @param int $userId
	 * @throws \Exception
	 * @return array
	 */
	public function getUser($userId){
		// Retrieve user
		$userArray = $this->_fetchUser($userId);
		if(empty($userArray)){
			throw new \Exception("Unable to retrieve user with UserID ".$userId);
		}
		
		// Return response
		if($this->_format === 'json'){
			return json_encode($userArray);
		} else {
			return $userArray;
		}
	}
}