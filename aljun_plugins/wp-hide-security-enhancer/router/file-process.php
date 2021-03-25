<?php

    /**
    * Load WordPress
    * Use ShortInit
    */
    define( 'SHORTINIT', true );
    
    //since we don't knwo the structure directory, we just have to guess
    $SCRIPT_NAME    =   $_SERVER['DOCUMENT_ROOT'] . '/' . $_SERVER['SCRIPT_NAME'];
    $SCRIPT_NAME    =   str_replace( '\\', '/', $SCRIPT_NAME);
    $SCRIPT_NAME_items  =   explode("/", $SCRIPT_NAME);
    
    //exclude last 4 as there's never a location for wp-load.php
    $SCRIPT_NAME_items  =   array_slice($SCRIPT_NAME_items, 0, count($SCRIPT_NAME_items) - 4);
    
    while(count($SCRIPT_NAME_items) >   0)
        {
            $location   =   implode('/', $SCRIPT_NAME_items);
            
            if(file_exists($location . '/wp-load.php'))
                {
                    require_once( $location . '/wp-load.php' );
                    break;
                }
                
            $SCRIPT_NAME_items  =   array_slice($SCRIPT_NAME_items, 0, count($SCRIPT_NAME_items) - 1);
            
        }
    
    if(!defined('ABSPATH'))
        die();
    
    $action             =   isset($_GET['action'])              ?   filter_var ( $_GET['action'],               FILTER_SANITIZE_STRING)       :   '';
    $file_path          =   isset($_GET['file_path'])           ?   filter_var ( $_GET['file_path'],            FILTER_SANITIZE_STRING)       :   '';
    $replacement_path   =   isset($_GET['replacement_path'])    ?   filter_var ( $_GET['replacement_path'],     FILTER_SANITIZE_STRING)       :   '';
    
    if(empty($action)   ||  empty($file_path)   ||  empty($replacement_path))
        die();
    
    include_once('class.file-processor.php');
        
    $WPH_FileProcess  =   new WPH_File_Processor($action, $file_path, $replacement_path);
    $WPH_FileProcess->run();    

?>