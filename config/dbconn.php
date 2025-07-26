<?php

/*  
        |---------------------------------------------------------------------- 
        |   Main database connections
        |-----------------------------------------------------------------------
        |   
        |   This is us es to connect mysql database
        |
        |
    */

namespace config;

use \PDO;
use InvalidArgumentException;

class dbh
{
    private $databases;

    public function __construct()
    {
        $this->databases = [
            //index 0
            [
                'host' => 'localhost',
                'username' => 'pefcarbo_admin',
                'password' => 'Balaod3.3r',
                'name' => 'pefcarbo_toolbox'
            ],

            //index 1
            [
                'host' => 'localhost',
                'username' => 'pefcarbo_admin',
                'password' => 'Balaod3.3r',
                'name' => 'pefcarbo_treeinfo'
            ],

            //index 2
            [
                'host' => 'localhost',
                'username' => 'pefcarbo_admin',
                'password' => 'Balaod3.3r',
                'name' => 'pefcarbo_pefcarbondata'
            ],

            //index 3
            [
                'host' => 'localhost',
                'username' => 'pefcarbo_admin',
                'password' => 'Balaod3.3r',
                'name' => 'pefcarbo_API'
            ],

            //index 4
            [
                'host' => 'localhost',
                'username' => 'pefcarbo_admin',
                'password' => 'Balaod3.3r',
                'name' => 'pefcarbo_nursery'
            ],
            // Add more databases as needed
        ];
    }

    public function connect($databaseIndex)
    {
        if (!isset($this->databases[$databaseIndex])) {
            throw new InvalidArgumentException("Database configuration not found for index $databaseIndex");
        }

        $config = $this->databases[$databaseIndex];
        $dsn = "mysql:host=" . $config['host'] . ";dbname=" . $config['name'];
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}

// Example usage
// $databaseManager = new \config\dbh(); // Adjust the namespace if needed
// $pdo1 = $databaseManager->connect(0);
