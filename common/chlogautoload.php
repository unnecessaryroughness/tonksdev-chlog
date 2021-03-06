<?php

    spl_autoload_register("chlogAutoload");

/*  ============================================
    FUNCTION:   __autoload
    PARAMS:     classname - name of class to load
    RETURNS:    (boolean) false
    PURPOSE:    loads miscellaneous classes 
                first attempts to load the path as supplied
                then attempts to load after removing the underscore suffix from the folder name
                then attempts to load from the common class library
    ============================================  */
    function chlogAutoload($classname) {
        
        //get file name only from the path\class name
        $parts = explode("\\", strtolower($classname));
        $file = end($parts).".php";
        $root = strlen($_SERVER["DOCUMENT_ROOT"]) > 0 ? $_SERVER["DOCUMENT_ROOT"] : ".";
        
        if ($parts[0] == "chlog") {            
            
            //remove the chlog\ prefix and flip slashes over
            array_shift($parts);
            $path = "/".implode("/", $parts)."/";
            $pathfile = $root.$path.$file;

            //if the path contains an underscore, remove it and the suffix 
            //from the file path but not the class name, to create a generalised directory
            $genpath = substr($path, 0, strrpos($path, "_"))."/";
            $genfile = $root.$genpath.$file;

            //define the common components path, in case both the original path and 
            //the generalised path are not found
            $commonpath = "/common/classes/";
            $commonfile = $root.$commonpath.$file; 

            //echo $pathfile."<br>".$genfile."<br>".$commonfile."<br>";
            //echo $pathfile."\n".$genfile."\n".$commonfile."\n".$root."\n";
            
            if (is_readable($pathfile)) { 
                require_once $pathfile; 
                
            } elseif (is_readable($genfile)){
                require_once $genfile;

            } elseif (is_readable($commonfile)){
                require_once $commonfile;        
            } 
        } 
    }

