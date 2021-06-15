<?php

namespace Core;

/**
 * View
 * 
 * PHP version 5.4 
 */
class View
{

    /**
     * Render a view file
     * 
     * @param string $view The view file
     * 
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  //relative to the Core directory

        if(is_readable($file))
        {
            require $file;
        }
        else
        {
            // echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     * 
     * @param string $Template The template file 
     * @param array $args Associative array of data to display in the view (optional)
     * 
     * @return void
     */
    public static function renderTemplate($Template, $args = [])
    {
        static $twig = null;

        if($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($Template,$args);
    }
}