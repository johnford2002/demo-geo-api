<?php
/**
 * Primary application extends Silex application
 *
 * @author John Ford <john.ford2002@gmail.com>
 * @since 2015-03-01
 */
namespace DemoGeoAPI;

use Symfony\Component\HttpFoundation\Request;

class Application extends \Silex\Application {

    /**
     * Perform basic setup of the application
     *
     * @return void
     */
    public function setup(){
        // Make sure all requests are validated prior to being run
        $this->before(array($this, "validateRequest"));

        // Load the database config
        $Config = $this->_loadDatabaseConfig();

        // Register and configure the database
        $this->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array (
                'driver'    => 'pdo_mysql',
                'host'      => $Config->host,
                'dbname'    => $Config->dbname,
                'user'      => $Config->user,
                'password'  => $Config->password
            ),
        ));

        // Register the default error handler
        $this->error(function (\Exception $e, $code) {
            $errorArray = array(
                "status"=>"error",
                "code"=>$code,
                "exceptionCode"=>$e->getCode(),
                "exceptionMessage"=>$e->getMessage()
            );

            return json_encode($errorArray);
        });
    }

    /**
     * Validate a request and throw an exception if the request body is not proper json
     *
     * @throws \Exception
     * @return void
     */
    public function validateRequest(Request $Request)
    {
        $method = $Request->getMethod();
        if($method === 'GET' || $method === 'OPTIONS') {
            return true;
        }

        // validate the request is a json type request
        $contentType = $Request->headers->get('Content-Type');
        if(0 === strpos($contentType, 'application/json')) {

            $content = $Request->getContent();
            if(in_array($method, array('PUT','POST','DELETE')) && !empty($content)) {
                
                $this->parsedRequest = json_decode($Request->getContent());

                if($this->parsedRequest === null) {
                    throw new \Exception('JSON input could not be validated, last json error: '. json_last_error());
                }
            }

        } else {
            throw new \Exception('Content-Type expected is application/json, ' . $contentType . ' received.');
        }
    }

    /**
     * Load the config object from a static JSON location
     *
     * @return object $Config
     */
    protected function _loadDatabaseConfig(){
        try{
            $Config = json_decode(file_get_contents("config/database.json"));
        } catch (Exception $e) {
            $Config = null;
        }

        $this->_validateConfig($Config);

        return $Config;
    }

    /**
     * Validate the config object for required fields necessary to connect to the DB
     *
     * @param object $Config
     * @throws \Exception
     * @return void
     */
    protected function _validateConfig($Config){
        if(empty($Config) || !is_object($Config) ||
           empty($Config->host) || empty($Config->dbname) ||
           empty($Config->user) || empty($Config->password))
        {
            throw new \Exception("Config is malformed. Please provide the following fields in json: host, dbname, user, password");
        }
    }
}