<?php 

    namespace chlog;

    class Frontpage_Control extends ChlogController {

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
        PURPOSE:    returns the relevant HTML markup for display, from the view object
        ============================================  */
        public function process($type, $fields) {
            
            switch ($type) {

                default:
                    return new Frontpage_View();    
                    break;
            }
        }
    }