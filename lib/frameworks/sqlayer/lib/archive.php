<?php

/***
    NOTE:
    
    This class does not handle reading from or writing to a file path. You will need to
    use file_get_contents($path) if reading from a file or, if writing out to file, use
    file_put_contents($archive->file,$path).

***/

abstract class SQLayerArchive
{

    protected $file;
    
    protected $rows;
    
    public function __construct()
    {
        $args = func_get_args();
        
        if (isset($args)) {
        
            if (is_array($args[0])) {
		
		        // process array into string
		        $this->rowsToFile($args[0]);
		
			} elseif (is_string($args[0])) {
			
			    // process string into array
			    $this->fileToRows($args[0]);
			
			}

        }
    }

    public function rows()
    {
        return $this->rows;
    }

    public function file()
    {
        return $this->file;
    }

}