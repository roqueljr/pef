<?php

namespace app\models;

require_once 'locker.php';

use InvalidArgumentException;
use \PDO;
use app\models\locker as lk;


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

    /**
     * This is mainly pair of "UPDATE" function for the insertion of new data from database that act as the base id as it returns newly inserted id to fill all the row data needs
     * @param string $table - actual table from database
     * @param string $column - base column where to insert new data base on choice only
     * @param string|int $value - base column value to insert data
     * @param int $index - select database connection
     * @return int - returns id of newly added data subjected to be followed by "UPDATE" function combination
     */
    public static function INSERT($table, $column, $value, $index = 0)
    {

        $s_index = lk::sanitize($index);
        $instance = new dbh();
        $db = $instance->connect($s_index);

        $stable = lk::sanitize($table);
        $sValue = lk::sanitize($value);
        $sColumn = lk::sanitize($column);

        $query = "INSERT INTO $stable ( $sColumn ) VALUES ( :value )";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":value", $sValue);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            //console('Base success!');
            return $db->lastInsertId();
        } else {
            //console('Base error!');
            return false;
        }
    }

    /**
     * Heavily relied by the database table id to update such data;
     * This is interconnected by "INSERT" funtion to insert new data from database
     * @param int $id - base id from table id to be updated
     * @param string $table - actual table from database
     * @param string $column - specific column to update from database
     * @param string|int $value - value specific column to update or modify
     * @param int $index - select database connection
     * @return bool
     */
    public static function UPDATE($id, $table, $column, $value, $index = 0)
    {
        $sIndex = lk::sanitize($index);
        $instance = new dbh();
        $db = $instance->connect($sIndex);

        $stable = lk::sanitize($table);
        $sValue = lk::sanitize($value);
        $sColumn = lk::sanitize($column);
        $sId = lk::sanitize($id);


        $query = "UPDATE $stable SET $sColumn = :value WHERE id = :id ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":value", $sValue);
        $stmt->bindParam(":id", $sId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            //console('Update success!');
            return true;
        } else {
            //console('Update failed!');
            return false;
        }
    }

    /**
     * This is function in deleting specific row in database using dynamic conditions
     * @param string $table - actual table from database
     * @param string $column - base column for the condition
     * @param string|int $value - value of the base column for the condition
     * @param int $index - select database connection
     * @return bool
     */
    public static function DELETE($table, $column, $value, $index = 0): bool
    {
        $sIndex = lk::sanitize($index);

        $instance = new dbh();
        $db = $instance->connect($sIndex);

        $stable = lk::sanitize($table);
        $sValue = lk::sanitize($value);
        $sColumn = lk::sanitize($column);

        $query = " DELETE FROM $stable WHERE $sColumn = :value ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":value", $sValue);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            //console(' DELETED SUCCESSFULLY!');
            return true;
        } else {
            //console(' DELETION FAILED!');
            return false;
        }
    }

    /**
     * This is use for sigle data retrieval from database
     * @param string $table - actual table from database
     * @param string $column - column to retrieve data
     * @param string $column2 - base column for certain condition
     * @param string|int $value - value of the base column for the condition
     * @param int $index - select database connection
     * @return mixed
     */

    public static function SELECT($table, $column, $column2, $value, $index = 0)
    {
        $sIndex = lk::sanitize($index);
        $instance = new dbh();
        $db = $instance->connect($sIndex);

        $stable = lk::sanitize($table);
        $sValue = lk::sanitize($value);
        $sColumn = lk::sanitize($column);
        $sColumn2 = lk::sanitize($column2);

        $query = "SELECT $sColumn FROM $stable ";
        $query .= "WHERE  $sColumn2 = :value ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":value", $sValue);
        //debugging only
        //echo $query;
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch()[$sColumn];
        } else {
            //console('DATA FETCHING ERROR!');
            return false;
        }
    }

    private static function conditionBuilder($value)
    {
        $combinedString = '';
        $result = [];
        $keyValuePairs = explode(',', $value);
        foreach ($keyValuePairs as $pair) {
            // Extract column name, conditional sign, and value
            list($key, $conditionalSign, $value) = explode(':', $pair, 3);
            $result[] = ['name' => $key, 'conditional_sign' => $conditionalSign, 'value' => $value];
        }

        foreach ($result as $data) {
            // Use parameter placeholders only for column names
            $combinedString .= locker::sanitize($data['name']) . ' ' . $data['conditional_sign'] . ' ' . '"' . locker::sanitize($data['value']) . '"' . ' AND ';
        }

        // Trim the string properly to remove unnecessary whitespace and the trailing 'AND'
        $combinedString = rtrim($combinedString, ' AND');

        return $combinedString;
    }

    /**
     * This method does something with the given parameters.
     *
     * @param string $table Specific table name.
     * @param array|null $array_condition Additional conditions in an associative array using format "name_of_table : sign : value" you add more by adding comma ",".
     * @param int $db_index select database from this class above.
     * @param string $column default is all but you can select specific columns.
     * @param string $orderBy add data ordering
     * @param string $groupBy group the data to specific column
     * @param string $limit limit number of rows.
     * 
     * @return array
     */

    public static function ARRAY($table, $array_condition = [], $db_index = 0, $column = '*', $orderBy = '', $groupBy = '', $limit = '')
    {
        $s_table = lk::sanitize($table);
        $s_db_index = lk::sanitize($db_index);
        $s_column = lk::sanitize($column);
        $s_orderBy = lk::sanitize($orderBy);
        $s_groupBy = lk::sanitize($groupBy);
        $s_limit = lk::sanitize($limit);


        $instance = new dbh();
        $db = $instance->connect($s_db_index);

        $sql = "SELECT $s_column FROM $s_table ";

        if ($array_condition) {
            $conditions = self::conditionBuilder($array_condition);
            $sql .= "WHERE $conditions ";
        }

        if ($s_orderBy) {
            $sql .= "ORDER BY $s_orderBy ";
        }

        if ($s_groupBy) {
            $sql .= "GROUP BY $s_groupBy ";
        }

        if ($s_limit) {
            $sql .= "LIMIT $s_limit ";
        }

        //echo $sql;
        $stmt = $db->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }
        return [];
    }

    //adding new methods here for more easy and advance data queries
    //take note to sanitize data first using locker class before using this methods

    /**
     * Summary of get
     * use to get sigle row or array
     * @param mixed $query - Custom SELECT SQl code queries
     * @param mixed $db_index - select database connection
     * @param mixed $type - selection between (1) array and (2) string or specific data
     * @return mixed
     */
    public static function get($query, $db_index = 0, $type = 'string')
    {
        $instance = new dbh();
        $db = $instance->connect($db_index);

        $stmt = $db->prepare($query);
        $stmt->execute();

        switch ($type) {
            case 'array':
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                }
                break;
            case 'string':
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
                }
                break;
            default:
                return false;
        }
    }

    /**
     * Summary of add
     * use to add new row data in such table
     * @param mixed $query - Custom INSERT SQl code queries
     * @param mixed $db_index - select database connection
     * @return mixed
     */
    public static function add($query, $db_index = 0)
    {
        $instance = new dbh();
        $db = $instance->connect($db_index);

        $stmt = $db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * modify data
     * @param mixed $query - sql code update
     * @param int $db_index - select database connection
     * @return bool
     */

    public static function modify($query, $db_index)
    {
        $instance = new dbh();
        $db = $instance->connect($db_index);

        $stmt = $db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }
}