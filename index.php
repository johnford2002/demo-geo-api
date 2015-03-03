<?php
require_once 'vendor/autoload.php';

use DemoGeoAPI\Application;

// Initialize the application
$App = new Application();

// Uncomment to enable debugging
//$App['debug'] = true;

// Run the app
$App->run();
