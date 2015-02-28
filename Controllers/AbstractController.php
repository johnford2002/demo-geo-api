<?php

/**
 * Abstract class for all controllers to extends.
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController {

    /**
     * Our application object.
     *
     * @var Silex\Application $_App
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
     * @param Application $app
     */
    public function __construct(Application $App, Request $Request, $format = 'json') {
        $this->_App = $App;
        $this->_Request = $Request;
        $this->_format = 'json';

        if (strpos($_SERVER["SERVER_NAME"], 'localhost-') !== false) {
            $this->_environment = "localhost";
        } else if (strpos($_SERVER["SERVER_NAME"], 'test-') !== false) {
            $this->_environment = "test";
        } else {
            $this->_environment = "production";
        }

    }

}


