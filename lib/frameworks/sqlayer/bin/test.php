<?php

/** import the library **/
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'ini.php');

class TestDB extends SQLayerDbo
{

    protected static $dbo;
    
    public static function dbo()
    {
        if (!isset(self::$dbo)) {
            self::$dbo = new self();
        }
        return self::$dbo;
    }

    public function __construct()
    {
        /** define where the database file is stored **/
        $this->dbDir = dirname(__DIR__).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'db';
        
        /** the base name of the file (no extension) **/
        $this->dbName = 'test';
        
        /** create the database connection **/
        parent::__construct();
    }

}

class ExampleTable extends SQLayerTable
{

    protected static $tbl;
    
    public static function tbl()
    {
        if (!isset(self::$tbl)) {
            self::$tbl = new self();
        }
        return self::$tbl;
    }

    public function __construct()
    {
        $this->dbo =  TestDB::dbo();
        $this->tableName = 'example';
        $this->columns = array(
            new SQLayerColumn('k','key','Key','INTEGER PRIMARY KEY',5),
            new SQLayerColumn('s','symbol','Symbol','TEXT',8),
            new SQLayerColumn('n','name','Name','TEXT',80)
        );
    }

}



$test = ExampleTable::tbl();
$test->createTable();
$test->insertRec(array(1,'AA','Alcoa Inc'));
$test->insertRec(array('CAT','Caterpillar Inc'));
print_r($test->allRecs());
