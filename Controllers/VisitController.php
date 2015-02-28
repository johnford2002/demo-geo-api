<?php
/**
 * Visit controller for all visit actions.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use \Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class VisitController extends AbstractController {

	public function createVisits($user){
		$RequestBody = json_decode($this->_Request->getContent());

		return json_encode($RequestBody);
	}

	public function updateVisits($user){
		$RequestBody = json_decode($this->_Request->getContent());

		return json_encode($RequestBody);
	}

	public function getVisits($user){
		$returnArray = array();

		$returnArray = ['visit1','visit2','visit3'];

		if($this->_format === 'json'){
			return json_encode($returnArray);
		} else {
			return $returnArray;
		}
	}
}