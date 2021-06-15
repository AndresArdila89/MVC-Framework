<?php

namespace Core;
use PDO;
use PDOException;
use App\Config;


#REVISION HISTORY:
#DEVELOPER          DATE            COMMENTS
#Andres Ardila      2021-04-10      db_connection.php file created
#Andres Ardila      2021-04-10      create DBConnect class
#Andres Ardila      2021-04-10      added try and catch to find errors in the connection
#Andres Ardila      2021-04-10      set the user-1921557 for the connection (only stored procedures and views)


/**
 * Base Model
 * 
 * PHP version 5.4
 */
abstract class Model{

    #PDO = PHP Data Object 
    #DSN = Data Source Name information require to connect to the database
    #DBH = Database Handle
    #STH = Statement handle
    #The PDO constructor receives dsn, username, password and options[]
    
    static public function getPDO(){

        static $pdo = null;
        
        try
        { 
            $dsn = "mysql:host=" . Config::DB_HOST . 
                   ";dbname=" . Config::DB_NAME . 
                   ";charset=" . Config::DB_CHARSET;

            $pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
        catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        
        return $pdo;
    }
}
?>