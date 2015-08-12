<?php

    namespace chlog;

    class Attack_Control extends ChlogController {

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
                
                case "update":
                    return new Attack_View();
                    break;
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    return new Attack_View();
                    break;
            }
        }

    }

