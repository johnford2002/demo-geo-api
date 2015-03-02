<?php
/**
 * Abstract class for all controllers to extend.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use DemoGeoAPI\Application;
use DemoGeoAPI\Service;
use \Symfony\Component\HttpFoundation\Request;

abstract class AbstractController {

    /**
     * Our application object.
     *
     * @var DemoGeoAPI\Application $_App
     */
    protected $_App = null;

    /**
     * Request Body
     *
     * @var \Symfony\Component\HttpFoundation\Request $_Request
     */
    protected $_Request = null;

    /**
     * Our application response format
     *
     * @var string $_format
     */
    protected $_format = null;

    /**
     * Our environment (localhost, test, production).
     *
     * @var string $_environment
     */
    protected $_environment = null;

    /**
     * Create our controller.
     *
     * @param Application $App
     * @param \Symfony\Component\HttpFoundation\Request $Request
     * @param string $format
     */
    public function __construct(Application $App, Request $Request, $format = 'json') {
        $this->_App = $App;
        $this->_Request = $Request;
        $this->_format = $format;

        if (strpos($_SERVER["SERVER_NAME"], 'localhost-') !== false) {
            $this->_environment = "localhost";
        } else if (strpos($_SERVER["SERVER_NAME"], 'test-') !== false) {
            $this->_environment = "test";
        } else {
            $this->_environment = "production";
        }
    }

    /**
     * Fetch the user record for the given userId
     *
     * @param int $userId
     * @return array
     */
    protected function _fetchUser($userId){
        // Instantiate UserDataService
        $UserDataService = new Service\UserDataService($this->_App['db']);

        // Lookup and return user array
        return $UserDataService->fetchByPrimaryKey($userId);
    }

    /**
     * Fetch the city record for the given city and state
     *
     * @param mixed $city
     * @param mixed $state
     * @return array
     */
    protected function _fetchCity($city, $state){
        // Instantiate CityDataService
        $CityDataService = new Service\CityDataService($this->_App['db']);

        // Determine state type
        $cityType = Service\CityDataService::getCityType($city);

        // Determine state type
        $stateType = Service\StateDataService::getStateType($state);

        // Lookup and return city array
        return $CityDataService->fetchByCityAndState($city, $state, $cityType, $stateType);
    }
}
