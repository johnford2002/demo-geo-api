<?php
require_once 'vendor/autoload.php';

use DemoGeoAPI\Application;
use DemoGeoAPI\Controllers;
use Symfony\Component\HttpFoundation\Request;

// Initialize the application
$App = new Application();

// Perform basic setup
$App->setup();

// Uncomment to enable debugging
//$App['debug'] = true;

/** BEGIN ROUTES **/

// Retrieve all cities in a given state
$App->get('/v1/states/{state}/cities', function($state, Request $Request) use ($App) {
    $Controller = new Controllers\CityController($App, $Request);
    return $Controller->getCitiesByState($state);
});

// Retrieve all cities in a given radius
$App->get('/v1/states/{state}/cities/{city}', function($state, $city, Request $Request) use ($App) {
    $radius = $Request->query->get('radius', false);

    if(empty($radius)){
        $App->abort(400, "No radius passed to find cities in range.");
    }

    $Controller = new Controllers\CityController($App, $Request);
    return $Controller->getCitiesByRadius($city, $state, $radius);
});

// Retrieve a given user
$App->get('/v1/users/{userId}', function($userId, Request $Request) use ($App) {
    $Controller = new Controllers\UserController($App, $Request);
    return $Controller->getUser($userId);
});

// Perform actions on user visits
$App->match('/v1/users/{userId}/visits', function($userId, Request $Request) use ($App) {
    $Controller = new Controllers\VisitController($App, $Request);

    switch ($Request->getMethod()) {

        case "POST":
            return $Controller->createVisits($userId);

        case "PUT":
            return $Controller->updateVisits($userId);

        case "GET":
            return $Controller->getVisits($userId);
    }
});

/** END ROUTES **/

$App->run();
