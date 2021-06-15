<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Post Model
 * 
 * PHP version 5.4
 */
class Post extends \Core\Model
{

    /**
     * Get all the posts as an associative array
     * 
     * @return array
     */
    public static function getAll()
    {
        try{

        $pdo = self::getPDO();
        $stmt = $pdo->query('SELECT id, title, content FROM posts ORDER BY created_at');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
}