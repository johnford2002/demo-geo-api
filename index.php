<?php
namespace DemoGeoAPI;
require_once 'vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;

$App = new \Silex\Application();

$App['debug'] = true;

$App->get('/hello/{name}', function ($name) use ($App) {
	return 'Hello '.$App->escape($name);
});

$App->get('/v1/states/{state}/cities', function($state, Request $Request) use ($App) {
    $Controller = new Controllers\CityController($App, $Request);
    return $Controller->fetchCitiesByState($state);
});

$App->get('/v1/states/{state}/cities/{city}?radius={distance}', function($state, $city, $distance, Request $Request) use ($App) {
    $Controller = new Controllers\CityController($App, $Request);
    return $Controller->fetchCitiesByRadius($state, $city, $distance);
});

$App->match('/v1/users/{user}/visits', function($user, Request $Request) use ($App) {
    $Controller = new Controllers\CityController($App, $Request);

    switch ($Request->getMethod()) {
        case "POST":
            return $Controller->createVisits($user);

        case "PUT":
            return $Controller->updateVisits($user);

        case "GET":
            return $Controller->getVisits($user);
    }
});

$App->run();