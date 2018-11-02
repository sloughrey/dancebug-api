<?php

namespace App;

class Database
{
    public static $db = null;

    /**
     * Returns our mysqli object, creates one if it hasn't been initialized yet
     *
     * @return object
     */
    public static function getDBObj()
    {
        try {
            if (!isset(static::$db)) {
                $host = 'localhost';
                $username = 'dancebug';
                $password = 'dancebug';
                $db_name = 'dancebug';
    
                $mysqli = new \mysqli($host, $username, $password, $db_name);
                if ($mysqli->connect_errno) {
                    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                    exit;
                }
    
                static::$db = $mysqli;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!static::$db) {
            throw new \Exception('Could not get the DB object');
        }

        return static::$db;
    }
}
