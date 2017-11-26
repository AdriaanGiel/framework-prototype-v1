<?php
namespace Homework\core\database;


class Db
{
    protected $db;

    /**
     * db constructor.
     * @param $db
     */
    public function __construct()
    {
        $this->db = $GLOBALS["BASE_DB_CONNECTION"];
    }

}