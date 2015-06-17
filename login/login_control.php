<?php 

    namespace chlog;

    class Login_Control {

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
                
                case "login":
                    //get login details from POST
                    $eml = safeget::kvp($fields, "email", "null@null.null", false);
                    $pwd = Security::chlogHash(safeget::kvp($fields, "password", "null", false));
                              
                    //attempt to create the user from the supplied details
                    //and add to session as the current user, then return page html
                    try {
                        $_SESSION["user"] = User::getUserFromEmail($eml, $pwd);
                        $errmsg = "User ".$eml." logged in"; Logger::log($errmsg);
                        
                        $vw = new Login_View();
                        $vw->loggedinuser = safeget::session("user", "nickname", null); 
                        $vw->loggedin = ($vw->loggedinuser);
                        return $vw;
                        
                    } catch (\Exception $e) {
                        unset($_SESSION["user"]);
                        return new Error_View(-1, "error retrieving user ".$eml." from login form ");
                    }
                    break;
                
                case "logout":
                    unset($_SESSION["user"]);
                    $vw = new Login_View();                    
                    $vw->loggedin = false;
                    return $vw;
                    break;
                
                case "unset":
                    //If the response action is unset then do nothing & show the form
                    $vw = new Login_View();
                    $vw->loggedinuser = safeget::session("user", "nickname", null);;
                    $vw->loggedin = ($vw->loggedinuser);
                    return $vw;
                    break;
                
                default:
                    //uh-oh - what is this? Throw an error!
                    unset($_SESSION["user"]);
                    return new Error_View(-1, "Unhandled response from page.".$type);
                    break;
            }
        }

        
    }


?>
