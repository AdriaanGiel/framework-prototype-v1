<?php
namespace Homework\core\database;


class Db
{
    private $connection;
    protected static $instance;

    /**
     * @return \PDO
     */
    public function getDbConnection(): \PDO
    {
        return $this->connection;
    }

    public static function getConnection()
    {
        if(is_null(static::$instance)){
            $db = new static();
            static::$instance = $db->getDbConnection();
        }
        return static::$instance;
    }

    /**
     * db constructor.
     * @param $db
     */
    protected function __construct()
    {
        $this->connection = new \PDO("mysql:host=". HOST .";dbname=".DB_NAME,USERNAME,PASSWORD);
    }

}