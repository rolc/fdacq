<?php

/***
    NOTES:
    
    This class does not handle the inclusion or exclusion of column headers. You can
    handle this by shifting the first element of the $rows property if neccessary.
    
    This class does not handle reading from or writing to a file path. You will need to
    use file_get_contents($path) if reading from a file or, if writing out to file, use
    file_put_contents($csv->file,$path).

***/

class SQLayerCsv
{

    /** @property *str* file text **/
    protected $file;
    
    /** @property *arr* array of row arrays **/
    protected $rows;

    /** @method constructor
      * @param  *arr* or *str*
      * @return void **/
    public function __construct()
    {
        $args = func_get_args();
        
        if (isset($args[0])) {
        
            if (is_array($args[0])) {
        
                /** process array into string **/
                $this->rowsToFile($args[0]);
        
            } elseif (is_string($args[0])) {
            
                /** process string into array **/
                $this->fileToRows($args[0]);
            
            }

        }
        
    }

    /** @method process quoted line
      * @param  *str* single line of comma separated values
      * @return *arr* values **/
    public static function processQuotedLine($line)
    {
        /** encode escaped quotes so as to ignore during processing **/
        if ($eqp = strpos($line, '\\"')) {
            $line = str_replace('\\"', '[XQZ]', $this->file);
        }

        /** convert string into character array **/
        $carr = str_split($line);
        $flag = 0;
        
        $vals = array();
        $cval = '';

        foreach ($carr as $char) {

            /** set a flag so we know if we are outside or inside quoted string **/
            if ($char == '"') {
                $flag = ( $flag == 0 ? 1 : 0 );
                continue;
            }

            /** if the character is a comma, check the flag to see what to do **/
            if ($char == ',') {
                if ($flag == 1) {
                    // inside, so don't terminate, keep adding
                    $cval .= $char;
                } else {
                    // outside, so terminate, replacing any encoded quotes
                    $vals[] = str_replace('[XQZ]', '\\"', $cval);
                    // reset the current value string
                    $cval = '';
                }
            } else {
                $cval .= $char;
            }
                
        }
        
        /** add the final value to the array **/
        $vals[] = $cval;
        
        return $vals;

    }

    /** @method convert file to array of rows
      * @param  *str* comma separated values
      * @return void **/
    public function fileToRows($str)
    {
        $this->file = $str;
        $this->rows = array();
        
        $lines = explode(PHP_EOL, $this->file);
        
        foreach ($lines as $line) {

            /** don't process empty lines **/
            if (strlen($line) > 0) {

                if ($neq = strpos($line, '"')) {
                    $this->rows[] = self::processQuotedLine($line);
                } else {
                    $this->rows[] = explode(',', $line);
                }

            }

        }
        
    }
    
    /** @method convert array of rows to file
      * @param  *arr* array of row arrays
      * @return void **/
    public function rowsToFile($rows)
    {

        $this->rows = $rows;
        $this->file = '';

        foreach ($rows as $vals) {
        
            $line_array = array();
        
            foreach ($vals as $value) {
            
                // escape quotes
                if ($anq = strpos($value, '"')) {
                    if ($eqp = strpos($value, '\\"')) {
                        // so we don't double-escape quotes
                        $value = str_replace('\\"', '[XQZ]', $value);
                    }
                    // bring in the rest
                    $value = str_replace('"', '[XQZ]', $value);
                    $value = str_replace('[XQZ]', '\\"', $value);
                }
                
                // now escape any commas
                if ($cmp = strpos($value, ',')) {
                    $line_array[] = '"'.$value.'"';
                } else {
                    $line_array[] = $value;
                }
            }
            
            $this->file .= implode(',',$line_array).PHP_EOL;
            
        }
    }

    /** @method rows getter
      * @param  void
      * @return *arr* array of row arrays **/
    public function rows()
    {
        return $this->rows;
    }

    /** @method file getter
      * @param  void
      * @return *str* file text **/
    public function file()
    {
        return $this->file;
    }

}
