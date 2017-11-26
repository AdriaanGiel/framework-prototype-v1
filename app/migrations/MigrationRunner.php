<?php

namespace Homework\migrations;


class MigrationRunner
{

    /**
     * MigrationRunner constructor.
     */
    public function __construct()
    {

    }

    public function run($db, $migrations)
   {
       $sql = "";

       foreach ($migrations as $migration){
           $sql .= $this->dropMigration($migration);
       }

       foreach ($migrations as $migration){
           $sql .= $this->setMigration($migration);
       }

       var_dump($sql);

       $db->exec($sql);
   }

   private function setMigration(Migration $migration)
   {
       $sql = "CREATE table " . $migration->table . " ( id INT (11) PRIMARY KEY AUTO_INCREMENT ,";
       $sql .= $this->setAttributes($migration->attributes);
       return $sql;
   }

   private function dropMigration(Migration $migration)
   {
        $sql = "DROP TABLE IF EXISTS ". $migration->table .";";
        return $sql;
   }


   private function setAttributes($attributes)
   {
       $sql = "";

       foreach ($attributes as $attribute) {
        $sql .= $this->getAttributeType($attribute);
       }

       $sql = rtrim($sql,",");

       $sql .= ");";

       return $sql;
   }

   private function getAttributeType(Attribute $attribute)
   {
       $name = $attribute->getName();

       switch ($attribute->getType())
       {
           case "string":
               return "$name VARCHAR(".$attribute->getNum().") NOT NULL,";
               break;

           case "int":
               return "$name INT(".$attribute->getNum().") NOT NULL,";
               break;
       }
       return "";
   }



}