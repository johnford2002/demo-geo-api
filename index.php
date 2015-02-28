<?php
namespace DemoGeoAPI;
require_once 'vendor/autoload.php';

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

$App->get('/v1/states/{state}/cities/{city}', function($state, $city, Request $Request) use ($App) {
    $radius = $Request->query->get('radius', false);

    if(empty($radius)){
        $App->abort(404, "No radius passed to find cities in range.");
    }

    $Controller = new Controllers\CityController($App, $Request);
    return $Controller->fetchCitiesByRadius($state, $city, $radius);
});

$App->match('/v1/users/{user}/visits', function($user, Request $Request) use ($App) {
    $Controller = new Controllers\VisitController($App, $Request);

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