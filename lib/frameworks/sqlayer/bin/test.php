<?php

// run "php <your/path/to>/fdacq/lib/frameworks/sqlayer/bin/test.php" to test on command line

/** import the library **/
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'ini.php');

/** EXAMPLE OF DBO CLASS IMPLEMENTED AS SINGLETON **/
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

/** EXAMPLE OF TABLE CLASS IMPLEMENTED AS SINGLETON **/
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

function test() {

	$start = microtime(true);

	echo '--------------TEST SQLAYER---------------'.PHP_EOL;

	/** access table singleton **/
	$test = ExampleTable::tbl();

	/** create the table **/
	$test->createTable();

	/** test explicit insertion with key **/
	$test->insertRec(array(1,'AA','Alcoa Inc'));

	echo '--------------TEST INSERT---------------'.PHP_EOL;

	/** test keyless insert **/
	$insert_id = $test->insertRec(array('BA','Boeing Co'));
	echo 'Insert ID was '.$insert_id.PHP_EOL;

	/** test insert with commas **/
	$test->insertRec(array(3,'CAT','Caterpillar, Inc'));

	echo '--------------TEST ALLRECS---------------'.PHP_EOL;

	/** print table records **/
	print_r($test->allRecs());

	echo '--------------TEST EXPORT---------------'.PHP_EOL;

	$path = dirname(__DIR__).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR.$test->tableName().'.csv';

	$test->exportToCsv($path,true);

	echo 'File Contents: '.PHP_EOL.file_get_contents($path).PHP_EOL;

	echo '------------TEST EMPTY TABLE------------'.PHP_EOL;

	$test->emptyTable();

	if ($recs = $test->allRecs()) {
		echo 'Method emptyTable() Failed'.PHP_EOL;
	} else {
		echo 'Table emptied'.PHP_EOL;
	}

	echo '--------------TEST IMPORT---------------'.PHP_EOL;

	$test->importFromCsv($path,true);

	print_r($test->allRecs());

	echo '--------------TEST COMPLETE---------------'.PHP_EOL;

	$end = microtime(true);

	echo 'RUNTIME '.($end - $start).' SECONDS'.PHP_EOL;

}

test();