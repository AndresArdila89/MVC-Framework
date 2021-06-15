<?php

namespace App\Controllers\Admin;

/**
 * User admin controller
 * 
 * PHP version 5.4
 */
class Users extends \Core\Controller
{

    /**
     * Before filter
     * 
     * @return void 
     */
    protected function before()
    {
        // Make sure an admin user id logged in for example
        // return false;
    }

    /**
     * Index Action
     * 
     * @return string The content of the action 
     */
    public function indexAction()
    {
        echo "This is the index action from the Admin users";
    }
}