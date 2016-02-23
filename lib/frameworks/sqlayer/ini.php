<?php

/** require library **/
$di = new DirectoryIterator(__DIR__.DIRECTORY_SEPARATOR.'lib');

foreach ($di as $file) {
    $fn = $file->getFilename();
    /** exclude files starting with dot **/
    if (substr($fn, 0, 1) != '.') {
        require_once(__DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.$fn);
    }
}
