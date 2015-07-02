<?php

    namespace chlog;

    class RecoverPW_Control {
 
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
                case "recover":
                    //recover the password
                    $eml = safeget::kvp($fields, "email", null, false);

                    if ($eml) {
                        try {
                            User::setRecoveryMode($eml);
                        } catch (\Exception $e) {
                            return new Error_View(-1, "Could not set user into recovery mode (".$e->getMessage().")");
                        }
                    } else {
                        return new Error_View(-1, "Could not set user into recovery mode - missing email address");
                    }
                
                    //successfully added user - redirect to the login/loggedin page
                    return new Redirect_View("/login/");
                    break;
                
                case "changepw":
                    $tok = safeget::kvp($fields, "rid", null, false);
                    
                    if ($tok) {
                        return new RecoverPW_View(true, $tok);
                    } else {
                        return new Error_View(-1, "Could not complete recovery mode - missing recovery ID");
                    }
                    break;
                
                case "complete":
                    $pwd = safeget::kvp($fields, "password", null, false);
                    $pw2 = safeget::kvp($fields, "passconf", null, false);
                    $tok = safeget::kvp($fields, "token", null, false);
                
                    if ($pwd && $pw2 && $tok && $pwd==$pw2) {
                        try {
                            User::completeRecoveryMode($tok, $pwd);
                            return new Redirect_View("/login/");
                        } catch (\Exception $e) {
                            return new Error_View(-1, "Could not complete recovery mode (".$e->getMessage().")");
                        }
                    } else {
                           
                    }
                    break;
                
                default:
                    return new RecoverPW_View();
                    break;
            }
        }
    
    }

