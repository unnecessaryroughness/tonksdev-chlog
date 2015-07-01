<?php 
    /*
      --------------------------------------------------------------------
      logger.php
      --------------------------------------------------------------------
      Defines the default logging class and logging functions.
      --------------------------------------------------------------------
    */

    namespace chlog;

    class Logger {

        const LOG_FILE_LOCATION = "/logs/chapplog";
                        
    /*  ============================================
        FUNCTION:   log (STATIC)
        PARAMS:     msg - message to log
                    xtra - an extra message to append to the main message in () 
        RETURNS:    boolean
        ============================================  */
        public static function log($msg, $xtra=null) {

            //append the xtra text in parentheses, if supplied
            //always add a new line at the end
            if ($xtra) {
                $msg .= " (" . $xtra . ")\n";   
            } else {
                $msg .= "\n";
            }
            
            //prepend the date time to the log message
            $logdt   = date("Y-m-d H:i:s");
            $loguser = safeget::session("user", "email", "[anon]", false); 
            $logurl  = safeget::server("REQUEST_URI", "[no url]", false); 
            $msg     = $logdt." | ".$loguser." | ".$logurl." | ".$msg;
            
            //find the logging directory
            $loglocation = safeget::server("DOCUMENT_ROOT", getcwd(), false).Logger::LOG_FILE_LOCATION;
            
            //only log if logging is turned on in the session settings. default to ON.
            if (safeget::session("environment", "logstatus", "ON") == "ON") {
                //write to the file. Throw an exception if it fails
                if (!file_put_contents($loglocation, $msg, FILE_APPEND)) {
                    throw new \Exception("Error writing to log file");
                } else {
                    return true;
                }
            }
        }
        
    }




 
