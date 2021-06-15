<?php

namespace Core;

/**
 * Router
 * 
 * PHP verion 5.4
 * version installed 8
 * 
 */
class Router
{
    /**
     * Associative array of routes (the routing table)
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from the matched route
     * @var array
     */
    protected $params = [];

    /**
     * Add a route to the routing table
     * 
     * @param string $route The route URL
     * @param array $params Parameters (controller, action, etc.)
     * 
     * @return void
     */
    public function add($route, $params = [])
    {
        // Find all the slash(/) and replace for skip regular expresion notation (\\/);
        $route = preg_replace("/\//","\\/",$route);

        // Convert variables ex: {controler}
        $route = preg_replace("/\{([a-z]+)\}/","(?P<$1>[a-z-]+)",$route);

        // Convert variables with custome regular expressions
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<$1>$2)',$route);

        // Add start and end delimiters, and case insesitive flag(i)
        $route = "/^" . $route . "$/i";
        
        // Add the converted route to the ROUTE TABLE 
        
        $this->routes[$route] = $params;
    }

    /**
     * Get all the routes from the routing table
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     * 
     * match is a Function added in php 8.
     * 
     * @param string $url The route URL
     * 
     * @return boolean true if a match found, false otherwise
     */
    public function routeMatch($url)
    {
        // Match to the fixed URL format /controller/action
        //$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        foreach($this->routes as $route => $params)
        {

            if(preg_match($route, $url, $matches))
            {

                foreach($matches as $key => $match)
                {
                    if(is_string($key))
                    {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
            return false;
    }

    /**
     * Get the currently matched parameters
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * Dispatch creates the controller and 
     * runs the action method
     * 
     * @param string $url The route URL
     * 
     * @return void
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if($this->routeMatch($url))
        {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            // $controller = "App\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;
            
            if(class_exists($controller))
            {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                /**
                 * FIXING SECURITY ISSU
                 * Action word needs to be remove from any action name
                 * passed by the URL
                 * 
                 * otherwise any user can use the magic __call to execute any
                 * method
                 */
                if(preg_match('/action$/i',$action) == 0)
                {
                    $controller_object->$action();
                }
                else
                {
                    throw new \Exception("Method $action in controller $controller 
                    cannot be called directly- remove the Action suffix to call 
                    this method");
                }

                // vulnerability this is not needed anymore since the controller
                // class takes care of the validation if the class exists or not
                //
                // if(is_callable([$controller_object, $action]))
                // {
                //     $controller_object->$action();
                // }
                // else
                // {
                //     echo "Method $action (in Controller $controller) no found";
                // }
            }
            else
            {
                // echo "Controller class $controller not found";
                throw new \Exception("Controller class $controller not found");

            }
        }
        else
        {
            // echo "Route $url not found";
            throw new \Exception("No route matched.",404);
        }
    }

    /**
     * Convert the string with hyphen to StudlyCaps,
     * ex: post-authors => PostAuthors
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ','', ucwords(str_replace('-',' ',$string)));
    }


    /**
     * Convert the string with hyphens to camelCase,
     * ex: add-new => addNew
     * 
     * @param string $string The string to convert
     * 
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variable from the URL (if any). As the 
     * full query string is used for the route, any variables at the end
     * will need to be removed before the route is matched to the routing table
     * For example:
     * 
     * @param string $url The full URL
     * 
     * @return string The URL with the query string varibles removed
     */
    protected function removeQueryStringVariables($url)
    {
        if($url != '')
        {
            $parts = explode('&',$url, 2);

            if(strpos($parts[0], '=') === false)
            {
                $url = $parts[0];
            }
            else
            {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the 
     * route parameters id added if present.
     * 
     * @return string The request URL
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->params))
        {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}