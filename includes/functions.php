<?php
/**
 * Shared fucntions
 * 
 * @since  2014-09-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */

/**
 * Outputs message onto error stream
 *
 * @since  2014-09-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */
function errorMessage($message) {
    fwrite(STDERR, $message);    
} // errorMessage()


/**
 * creates missing folders
 *
 * @since  2014-09-16
 * @author Patrick Forget <patforg@geekpad.ca>
 */
function createMissingDir($filename, $isDir = true) {
    if ($isDir !== true) {
        $dir = dirname($filename);
    } else {
        $dir = $filename; 
    } //if 
    
    $itemsToCreate = array();

    while (!file_exists($dir)) {
        array_unshift($itemsToCreate, $dir);
        $dir = dirname($dir);
    } //while

    foreach($itemsToCreate as $item) {
        mkdir($item, 0755);
    } //foreach
    
} // createMissingDir()


