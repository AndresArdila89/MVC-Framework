<?php

namespace Core;

/**
 * Base controller
 * 
 * PHP version 5.4
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Class constructor
     * 
     * @param array $route_params Parameters fron the route
     * 
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;   
    }

    /**
     * MAGIC METHOD __call
     * 
     * ACTION FILTERS with suffix 
     * 
     * @param string $name This is the name of the non-exixting or non-public method
     * 
     * @param array $args arguments from the method
     * 
     * @return void
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if(method_exists($this,$method))
        {
            if($this->before() !== false)
            {
                call_user_func_array([$this,$method], $args);
                $this->after();
            }
        }
        else
        {
            echo "Method $method not found in controller " . get_class($this);
        }
    }

    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function before()
    {}

    /**
     * After filter - called after an action method.
     */
    protected function after()
    {}


}