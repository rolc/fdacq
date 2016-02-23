<?php

/***
    NOTES:
    
    This class does not handle the inclusion or exclusion of column headers. You can
    handle this by shifting the first element of the $rows property if neccessary.
    
    This class does not handle reading from or writing to a file path. You will need to
    use file_get_contents($path) if reading from a file or, if writing out to file, use
    file_put_contents($xls->file,$path).

***/

class SQLayerXls extends SQLayerArchive
{

    public function fileToRows($str)
    {
        $this->file = $str;
        $this->rows = array();
        
        // set up a temporary array to hold xml strings
        $xrs = array();
        
        while ($stp = strpos($str, '<Row')) {
        
            // clip anything before the next row
            $str = substr($str, $stp);
            
            // find the closing tag
            $end = strpos($str, '</Row') + 6;
            
            // add the xml to the temp array
            $xrs[] = substr($str, 0, $end);
            
            // strip out what has been extracted
            $str = substr($str, $end);
        
        }
        
        // now convert the cells to values
        foreach ($xrs as $xr) {
        
            $values = array();
            
            while ($stp = strpos($xr, '<Data')) {
            
                // clip anything before the next cell
                $xr = substr($xr, $stp);
                
                // find the closing tag
				$end = strpos($xr, '</Data') + 7;
				
				// add the cell 
				$values[] = strip_tags(substr($xr, 0, $end));
				
				// strip out what has been extracted
				$xr = substr($xr, $end);
				
            }
            
            $this->rows[] = $values;
        
        }
        
        
    }
    
    public function rowsToFile($rows)
    {
        $this->file = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40"><DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"><Author>SQLayer</Author><LastAuthor> SQLayer </LastAuthor><Created>'.date('D M d H:i:s').' EST '.date('Y').'</Created><LastSaved>'.date('D M d H:i:s').' EST '.date('Y').'</LastSaved><Company>ROLCapital, Inc.</Company><Version>11.9999</Version></DocumentProperties><ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel"><WindowHeight>10760</WindowHeight><WindowWidth>20480</WindowWidth><WindowTopX>0</WindowTopX><WindowTopY>0</WindowTopY><ProtectStructure>False</ProtectStructure><ProtectWindows>False</ProtectWindows></ExcelWorkbook>'.PHP_EOL.'<Worksheet>'.PHP_EOL;
        $this->rows = $rows;
        
        foreach ($rows as $row) {
            
            $this->file .= '<Row>'.PHP_EOL;
            
            foreach ($row as $value) {
            
                $this->file .= '<Cell><Data>';
                
                $this->file .= $value;
            
                $this->file .= '</Data></Cell>'.PHP_EOL;
            }
            
            $this->file .= '</Row>'.PHP_EOL;

        }

        $this->file .= '</Worksheet>'.PHP_EOL.'</Workbook>'.PHP_EOL;

    }

}