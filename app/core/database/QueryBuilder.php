<?php

namespace Homework\core\database;

use Homework\core\helpers\Collection;

class QueryBuilder extends Db
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var int
     */
    public $id;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var string
     */
    private $query;

    /**
     * @var \PDO
     */
    private $connection;


    private $bind;


    public function createNew(array $data)
    {
        $queryData = [];
        $this->query = "INSERT INTO ". $this->table . "(";
        $part = "";

        $data = new Collection($data);
        $data = $this->filterFillable($data);

        foreach ($data as $key => $item){
            $this->query .= "$key,";
            $part .= ":$key,";
            $queryData[":$key"] = $item;
        }

        $this->bind = $data;

        $this->query = rtrim($this->query,",");
        $this->query .= " ) VALUES ( " . rtrim($part,",") . ")";

        return $this->queryBuilder($this->query,true)->getLastInserted();
    }

    public function updateRow(array $data)
    {
        $this->query = "UPDATE " . $this->table . " SET ";

        $data = new Collection($data);
        $data = $this->filterFillable($data);

        $array = [];

        $array[":id"] = $this->id;

        foreach ($data as $key => $item){
            $this->query .= "$key = :$key,";
            $array[":$key"] = $item;
        }
        $this->query = rtrim($this->query,",");
        $this->query .= " WHERE id = :id";

        $this->bind =  $array;


        return $this->queryBuilder($this->query);
    }

    public function deleteRow()
    {
        $this->query = "DELETE FROM ". $this->table ." WHERE id = :id";
        $this->bind = [
          ':id' => $this->id
        ];

        return $this->queryBuilder($this->query);
    }

    public function findOrFail($id)
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $this->bind = [
            ":id" => $id
        ];

        return $this->queryBuilder($this->query)->fetchOne();
    }

    protected function generateObject($data)
    {
        if(!empty($data)){
           $object = new $this();
           foreach ((array)$data as $key => $item){
               $object->{$key} = $item;
           }
           return $object;
       }
       else{

           include __DIR__ . "/../views/header.php";

           include __DIR__ . "/../views/Errors/404.php";

           include __DIR__ . "/../views/footer.php";
           exit;
       }
    }

    private function getLastInserted()
    {
        return $this->findOrFail($this->id);
    }

    public function getAllQuery()
    {
        $this->query = "SELECT * FROM " . $this->table;
        return $this->queryBuilder($this->query)->fetchAll();
    }

    public function getColumn($columns)
    {
        $this->query = "SELECT " . implode(",",$columns) . "FROM" . $this->table;
        return $this->queryBuilder($this->query)->fetchAll();
    }

    public function whereQuery(array $data)
    {
        $this->query = "SELECT * FROM ". $this->table . " WHERE (";
        $where = "";

        foreach ($data as $key => $item) {
            $where .= "AND $key = :$key";
        }

        $this->bind = $data;

        $this->query .= trim($where,"AND") . " )";

        return $this;
    }

    //TODO fix or where
    public function orWhereQuery(array $data)
    {
        $this->query .= " OR ( ";
        $or = "";

        foreach($data as $key => $item){
            $or .= "AND $key = ':$key'";
        }

        $this->bind += $data;

        $this->query .= trim($or,"AND") . " )";

        return $this;
    }

    public function getResults()
    {
        return $this->queryBuilder($this->query)->fetchAll();
    }

    private function fetchOne()
    {
        return $this->generateObject($this->connection->fetch(\PDO::FETCH_OBJ));
    }

    private function fetchAll()
    {
        $collection = new Collection($this->connection->fetchAll(\PDO::FETCH_OBJ));

        $test = $collection->map(function($object){
            return $this->generateObject($object);
        });

        return $test;
    }

    public function queryBuilder($query,$id = false)
    {

        $this->connection = $this->db->prepare($query);

        if(count($this->bind) != 0)
        {
            foreach ($this->bind as $key => &$item){
                $this->connection->bindParam($key,$item);
            }
        }

        $this->connection->execute();

        if($id)
        {
            $this->id = $this->db->lastInsertId();
        }

        return $this;
    }

    /**
     * @param array $data
     * @param $check
     * @return array
     */
    public function filterFillable(Collection $data)
    {
        $check = $this->fillable;
        $data = $data->filterWithKey(function ($item, $key) use ($check) {
            return in_array($key, $check);
        })->all();
        return $data;
    }

}