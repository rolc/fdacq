<?php

abstract class SQLayerTable
{
    
    /** @property *str* table name **/
    protected $tableName;
    
    /** @property *obj* SQLayerDbo **/
    protected $dbo;
    
    /** @property *arr* array of SQLayerColumn objects **/
    protected $columns;
    
    /** @method get record from key
      * @param  *int* integer key
      * @return *arr* assoc (or false) **/
    public function recFromKey($key)
    {
        /** compose sql **/
        $sql = 'SELECT * FROM "'.$this->tableName.'" WHERE "k" = '.$key.';';

        /** return the first record (or false) **/
        if ($recs = $this->dbo->fetchRecs($sql)) {
            foreach ($recs as $rec) {
                return $rec;
            }
        } else {
            return false;
        }
 
    }

    /** @method get record from key
      * @param  void
      * @return *arr* array of assocs (or false) **/
    public function allRecs()
    {
        /** compose sql **/
        $sql = 'SELECT * FROM "'.$this->tableName.'";';

        /** return the array of recs (or false) **/
        if ($recs = $this->dbo->fetchRecs($sql)) {
            return $recs;
        } else {
            return false;
        }

    }

    /** @method insert record
      * @param  *arr* simple array of values in sequence
      * @return *int* id (key) of inserted record **/
    public function insertRec($values)
    {
        /** compose sql **/
        $sql = 'INSERT INTO "'.$this->tableName.'" VALUES (';

        if (count($values) == (count($this->columns) - 1)) {
            /** prepend unquoted null, comma and opening quote **/
            $sql .= 'NULL, "'
        } elseif (count($values) == count($this->columns)) {
            /** prepend just the opening quote **/
            $sql .= '"';
        } else {
            /** get me out of here **/
            return false;
        }

        $sql .= implode('","', $values).'");';
        
        /** return the last insert id (or false) **/
        if ($result = $this->dbo->executeSQL($sql)) {
            return $this->dbo->lastInsertId();
        } else {
            return false;
        }

    }

    /** @method create table
      * @param  void
      * @return *int* 0 (or false) **/
    public function createTable()
    {
        /** compose sql **/
        $sql = 'CREATE TABLE "'.$this->tableName.'" (';
        
        $x = 1;
        foreach ($this->columns as $col) {
            $sql .= '"'.$col->char.'" '.$col->type;
            if ($x < count($this->columns)) {
                $sql .= ', ';
            }
            $x++;
        }
        $sql .= ');';
        
        /** return 0 for success or false **/
        return $this->dbo->executeSQL($sql);

    }

}
