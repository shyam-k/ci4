<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/student', 'StudentController::index');
$routes->post('/student/saveMarks', 'StudentController::saveMarks');
$routes->get('/eligibility', 'StudentController::eligibility');