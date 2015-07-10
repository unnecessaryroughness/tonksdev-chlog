<?php

    namespace chlog;

    class About_Control {
 
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none_
        RETURNS:    (object)
        PURPOSE:    constructs the class 
        ============================================  */
        public function __construct() {}
        
        
    /*  ============================================
        FUNCTION:   process
        PARAMS:     type    - the type of process that needs to be run
                    fields  - a key/value pair of field data to support the process
        RETURNS:    (string) HTML data
        PURPOSE:    returns the relevant view object for display
        ============================================  */
        public function process($type, $fields) {
            
            switch ($type) {
                case "about":
                    //show default "about" view
                    return new About_View();
                    break;
                
                default:
                    //in case of emergency, redirect to home page
                    return new Redirect_View("/");
                    break;
            }
        }
    
    }

