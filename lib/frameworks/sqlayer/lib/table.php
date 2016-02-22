<?php

abstract class SQLayerTable
{
    
    /** @property *str* table name **/
    protected $tableName;
    
    /** @property *obj* SQLayerDbo **/
    protected $dbo;
    
    /** @method get record from key
      * @param  *int* integer key
      * @return *arr* assoc (or false) **/
    public function recFromKey($key)
    {
        /** compose sql **/
        $sql = 'SELECT * FROM "'.$this->tableName.'" WHERE "key" = '.$key.';';

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

}
