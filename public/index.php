<?php
/**
 * Front Controller
 * 
 * PHP version 5.4
 * Install version PHP 8
 * 
 */
/**
 * Composer autoloader
 * 
 * loads Twig 3
 * loads all classes
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Require the controller class
//require '../App/Controllers/Posts.php';

/**
 * Autoloader
 */
// spl_autoload_register(
//     function ($class)
//     {
//         $root = dirname(__DIR__); // get the parent directory
//         $file = $root . '/' . str_replace('\\','/',$class) . '.php';
//         if(is_readable($file)){
//             require $root . '/' . str_replace('\\','/', $class) . '.php';
//         }

//     });

/**
 * Error and Exception handling 
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
//require '../Core/Router.php';

$router = new Core\Router();

// echo get_class($router);

// Add the routes
$router->add('',['controller' => 'Home','action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}',['namespace' => 'Admin']);
// $router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
// $router->add('posts/new',['controller' => 'Posts', 'action' => 'new']);
// $router->add('admin/{action}/{controller}');
// $router->add('admin/{action}/{id:\d+}/{controller}');


/*
// Get the QUERY STRING from the URL
$url = $_SERVER['QUERY_STRING'];
    echo "<pre>";
    echo htmlspecialchars(print_r($router->getRoutes(),true));
    echo "</pre>";

// Match the requested route
if($router->routeMatch($url))
{

    echo "<pre>";
    var_dump( $router->getParams());
    echo "</pre>";
}
else
{
    echo "sorry this route '$url' could not be found 404 error";
}
*/
$router->dispatch($_SERVER['QUERY_STRING']);
