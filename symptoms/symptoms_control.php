<?php

    namespace chlog;

    class Symptoms_Control extends ChlogController {

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
                
                
                default:
                    $eml = safeget::session("user", "email", null);
                    $sl = Lookups::getSymptomsList($eml);
                    return new Symptoms_View($sl);
                    break;
            }
        }

    }

