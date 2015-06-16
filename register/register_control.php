<?php

    namespace chlog;

    class Register_Control {
 
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
                            $vw = new Error_View();
                            $vw->errcode = -1;
                            $vw->errmsg = "Could not register user: database error (".$e->getMessage().")";
                            return $vw;
                        }
                    } else {
                        $vw = new Error_View();
                        $vw->errcode = -1;
                        $vw->errmsg = "Could not register user - missing mandatory fields";
                        return $vw;
                    }
                
                    //redirect to the login/loggedin page
                    $vw = new Login_View();
                    $vw->loggedinuser = safeget::session("user", "nickname", null); 
                    $vw->loggedin = ($vw->loggedinuser);
                    return $vw;
                    break;
                
                default:
                    $vw = new Register_View();
                    return $vw;
                    break;
            }
        }
    
    }

