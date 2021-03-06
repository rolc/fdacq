<?php

/***
    Struct with a constructor
    Example $column = new SQLayerColumn('k','key','Key','INTEGER PRIMARY KEY',5);

    Column names in the sqlite file are a single character to speed up database
    interaction. Use the "$column->varName" property for object properties or
    array keys and the "$column->title" property for display headers.
***/

class SQLayerColumn
{

    /** @property *str* column character **/
    public $char;
    
    /** @property *str* name for property or assoc key **/
    public $varName;
    
    /** @property *str* column title **/
    public $title;

    /** @property *str* SQLite type **/
    public $type;
    
    /** @property *int* column length **/
    public $length;

    /** @method constructor
      * @param  5 properties in above order
      * @return void **/
    public function __construct($char,$varName,$title,$type,$length)
    {
        $this->char = $char;
        $this->varName = $varName;
        $this->title = $title;
        $this->type = $type;
        $this->length = $length;
    }

}
