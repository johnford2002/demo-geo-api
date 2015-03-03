<?php
require_once 'vendor/autoload.php';

use DemoGeoAPI\Application;
use DemoGeoAPI\Controllers;
use Symfony\Component\HttpFoundation\Request;

// Initialize the application
$App = new Application();

// Uncomment to enable debugging
//$App['debug'] = true;

// Run the app
$App->run();
