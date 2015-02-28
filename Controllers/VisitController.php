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
		$body = json_decode($this->request->getContent());
	}

	public function updateVisits($user){
		$body = json_decode($this->request->getContent());
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