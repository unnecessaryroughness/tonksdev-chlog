<?php

    namespace chlog;

    class Activate_Control extends ChlogController {
 
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
                case "activate":
                    //register the user
                    $tok = safeget::kvp($fields, "aid", null, false);

                    if ($tok) {
                        try {
                            User::setActive($tok);
                        } catch (\Exception $e) {
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }
                    } else {
                        return new Error_View(ChlogErr::EC_FAILEDACTIVATION, ChlogErr::EM_FAILEDACTIVATION);
                    }
                
                    //successfully added user - redirect to the login/loggedin page
                    return new Activate_View();
                    break;
                
                default:
                    return new Redirect_View("/login/");
                    break;
            }
        }
    
    }

