<?php 

/*  ============================================
    FUNCTION:   __autoload
    PARAMS:     classname - name of class to load
    RETURNS:    (boolean) false
    PURPOSE:    loads miscellaneous classes 
                first attempts to load the path as supplied
                then attempts to load after removing the underscore suffix from the folder name
                then attempts to load from the common class library
    ============================================  */
    function __autoload($classname) {

        //get file name only from the path\class name
        $parts = explode("\\", strtolower($classname));
        $file = end($parts).".php";

        //get file path (but not file name) from class name. Flip slashes over & add to ROOT
        $path = "/".str_replace("\\", "/", strtolower($classname))."/";
        $pathfile = $_SERVER['DOCUMENT_ROOT'].$path.$file;
        
        //if the path contains an underscore, remove it and the suffix 
        //from the file path but not the class name, to create a generalised directory
        $genpath = substr($path, 0, strrpos($path, "_"))."/";
        $genfile = $_SERVER['DOCUMENT_ROOT'].$genpath.$file;
            
        //define the common components path, in case both the original path and 
        //the generalised path are not found
        $commonpath = "/chlog/common/classes/";
        $commonfile = $_SERVER['DOCUMENT_ROOT'].$commonpath.$file; 

        //echo "path: ".$pathfile."<br>genpath: ".$genfile."<br>commonpath: ".$commonfile."<br><br>";
        
        if (file_exists($pathfile)) { 
            require_once $pathfile; 
            
        } elseif (file_exists($genfile)){
            require_once $genfile;
            
        } elseif (file_exists($commonfile)){
            require_once $commonfile;
            
        } else {
            $errmsg = "Error auto-loading class ".$path.end($parts);
            chlog\Logger::log($errmsg); throw new \Exception($errmsg);
        }
    }



/*  ============================================
    FUNCTION:   html
    PARAMS:     text - text to convert to safe HTML
    RETURNS:    (string) html escaped text 
    PURPOSE:    strips unsafe content from strings to be output in HTML pages 
    ============================================  */
    function html($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

/*  ============================================
    FUNCTION:   htmlout
    PARAMS:     text - unsafe html text to echo to the screen
    RETURNS:    (io) outputs directly to the screen 
    PURPOSE:    makes unsafe text safe for output and outputs directly to the screen
    ============================================  */
    function htmlout($text)
    {
        echo html($text);
    }


/*  ============================================
    FUNCTION:   safesessionstart
    PARAMS:     none
    RETURNS:    (boolean) indicates if session was started or not
    PURPOSE:    starts the PHP session, but only if it isn't already running 
    ============================================  */
    function safesessionstart()
    {
        if(session_id() == '') {
            session_start();
            return true;
        } else {
            return false;
        }
    }




/*  ============================================
    FUNCTION:   magicquoteshandler
    PARAMS:     none
    RETURNS:    (none)
    PURPOSE:    Makes legacy problems with magic quotes go away 
    ============================================  */
    function magicquoteshandler() {
        if (get_magic_quotes_gpc())
        {
          $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
          while (list($key, $val) = each ($process))
          {
            foreach ($val as $k => $v) 
            {
              unset($process[$key][$k]);
              if (is_array($v))
              {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
              }
              else
              {
                $process[$key][stripslashes($k)] = stripslashes($v);
              }
            }
          }
          unset($process);
        }
    }

/*  ============================================
    >>> IMMEDIATE CODE <<< 
    ============================================  */

    //run the magic quotes handler every time
    magicquoteshandler();
