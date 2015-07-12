<?php 


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
        if(session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            return true;
        } else {
            return false;
        }
    }


/*  ============================================
    FUNCTION:   getNiceErrorMessage
    PARAMS:     $e = error object
    RETURNS:    (string) error message to display to the user
    PURPOSE:    translates the error code into a nice error message for display.
                if there is no nice error message defined, returns the default
                error text instead.
    ============================================  */
    function getNiceErrorMessage($e) {
/*
        if (isset(chlog\ChlogErr::$EA_USERERRORS[$e->getCode()])) {
            return chlog\ChlogErr::$EA_USERERRORS[$e->getCode()];
        } else {
            return $e->getMessage();
        }                        
*/
        
        $errsources = array(chlog\ChlogErr::$EA_USERERRORS,
                            chlog\ChlogErr::$EA_NAVERRORS);
        
        foreach ($errsources as $es) {
            if (isset($es[$e->getCode()])) {
                return $es[$e->getCode()];   
            }
        }
        
        return $e->getMessage();
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
