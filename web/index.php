<?php

require_once 'vendor/autoload.php';
namespace DemoGeoAPI;

//error_reporting(E_ALL);

//-- Setup autoloader
spl_autoload_register(
    $localAutoload = function ($className)
    {
        if (stripos($className, 'DemoGeoAPI\\') === 0) {
            $className = str_replace('DemoGeoAPI\\', '', $className);
            $className = str_replace('\\', '/', $className) . '.php';

            if (is_file($className)) {
                require_once($className);
                return true;
            }
        }
        
        return false;
    },
    false,
    true
);

$App = new Silex\Application();

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