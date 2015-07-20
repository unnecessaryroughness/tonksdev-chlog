<?php

    namespace chlog;

    class Register_Control extends ChlogController {
 
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
        PURPOSE:    returns the relevant HTML markup for display, from the login view object
        ============================================  */
        public function process($type, $fields) {
            
            switch ($type) {
                case "register":
                    //register the user
                    $eml = safeget::kvp($fields, "email", null, false);
                    $nnm = safeget::kvp($fields, "nickname", null, false);
                    $bio = safeget::kvp($fields, "biography", "None.", false);
                    $npw = safeget::kvp($fields, "password", null, false);
                    $np2 = safeget::kvp($fields, "passconf", null, false);

                    if ($eml && $nnm && $npw && $np2) {
                        try {
                            $_SESSION["user"] = User::registerUser($eml, $nnm, $bio, $npw, $np2);
                            
                        } catch (\Exception $e) {
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }
                    } else {
                        return new Error_View(ChlogErr::EC_MISSINGFIELDS, ChlogErr::EM_MISSINGFIELDS);
                    }
                
                    //successfully added user - redirect to the login/loggedin page
                    return new Redirect_View("/chlog/login/");
                    break;
                
                default:
                    return new Register_View();
                    break;
            }
        }
    
    }

