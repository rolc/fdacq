<?php

class SQLayerJson extends SQLayerArchive
{

    protected $keys;

    public function __construct() { }
    
    /** @method init with keys and rows (bypass constructor)
      * @param  *arr* keys, *arr* array of assoc rows
      * @return void **/
    public function initWithKeysAndRows($keys,$rows)
    {
        $this->keys = $keys;
        
        $this->rowsToFile($rows);

    }

    /** @method init with string (bypass constructor)
      * @param  *str* json
      * @return void **/
    public function initWithString($json)
    {
        $this->fileToRows($json);

    }

    /** @method convert json string to rows
      * @param  *str* comma separated values
      * @return void **/
    public function fileToRows($str)
    {
        $this->file = $str;

        $objects = json_decode($str, false);

        $this->rows = array();

        foreach ($objects as $object) {

            $row = array();

            foreach ($object as $key=>$val) {
                $row[] = $val;
            }

            $this->rows[] = $row;

        }
        
    }
    
    /** @method convert array of rows to json string
      * @param  *arr* array of row arrays
      * @return void **/
    public function rowsToFile($rows)
    {

        $this->rows = $rows;

        $object = array();

        foreach ($rows as $vals) {
        
            /* associate column names */
            $assoc = array();
            
            $x = 0;
            
            foreach ($vals as $key=>$val) {
            
                $assoc[$this->keys[$x]] = $val;
                
                $x++;
            }
            
            $object[] = $assoc;
            
        }
        
        $this->file = json_encode($object);
        
    }

}
