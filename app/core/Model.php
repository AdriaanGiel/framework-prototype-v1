<?php

namespace Homework\core;

use Homework\core\database\QueryBuilder;
use Homework\core\helpers\Collection;

class Model extends QueryBuilder
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function all()
    {
        $object = new static();

        return $object->getAllQuery();
    }

    public static function create(array $data)
    {
        $object = new static();

        $uniqueObject = $object->checkUnique($data);
        if(count($uniqueObject->all()) != 0)
        {
            return $uniqueObject->first();
        }


        return $object->createNew($data);
    }

    private function checkUnique($target)
    {
        if($this->unique != ""){
            $check = $this->where([
                $this->unique => $target[$this->unique]
            ])->get();

            return $check;
        }

        return new Collection([]);
    }

    public static function find(int $id)
    {
        $object = new static();

        return $object->findOrFail($id);
    }

    public static function where(array $where)
    {
        $object = new static();
        return $object->whereQuery($where);
    }

    public function orWhere(array $orWhere)
    {
        return $this->orWhereQuery($orWhere);
    }

    public function get()
    {
        return $this->getResults();
    }

    public function delete()
    {
        return $this->deleteRow();
    }

    public function update($data)
    {
        return $this->updateRow($data);
    }

    public function save()
    {
        $data = new Collection(get_object_vars($this));
        $compare = $this->fillable;

        return $this->createNew($data->filterWithKey(function ($attribute,$key) use ($compare) {
            return in_array($key,$compare);
        })->all());
    }




}