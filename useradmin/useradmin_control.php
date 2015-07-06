<?php

    namespace chlog;

    class Useradmin_Control {
 
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
                    $pwd = safeget::kvp($fields, "password", null, false);
                    $pw2 = safeget::kvp($fields, "passconf", null, false);
                    $pw3 = safeget::kvp($fields, "passconf2", null, false);
                    $nnm = safeget::kvp($fields, "nickname", "unknown", false);
                    $bio = safeget::kvp($fields, "biography", "Not given", false);
                    $usr = safeget::session("user", null, null, false);
                
                    if ($pwd) {
                        if (isset($usr)) {
                            $usr->setNickName($nnm);
                            $usr->setBiography($bio);
                            
                            try {
                                //Update basic details
                                if (!$usr->flushToDB($pwd)) {
                                    $errmsg = "Failed to update details for user ";
                                    Logger::log($errmsg.$usr->email); 
                                    return new Error_View(-1, $errmsg);
                                }
                                //If passwords 2 & 3 are set, update the password
                                if ($pw2 && $pw3) {
                                    if ($usr->setPassword ($pwd, $pw2, $pw3)) {   
                                        Logger::log("Changed password for user ".$usr->email);
                                        return new Useradmin_View($usr);
                                    } else {
                                        $errmsg = "Failed to change password for user ".$usr->email;
                                        Logger::log($errmsg); return new Error_View(-1, $errmsg);
                                    }
                                } else {
                                    throw new \Exception("", ChlogErr::EC_USERPWDSNOTMATCHED);
                                }
                                
                                return new Useradmin_View($usr);
                                
                            } catch (\Exception $e) {
                                Logger::log(getNiceErrorMessage($e), $usr->email); 
                                return new Error_View($e->getCode(), getNiceErrorMessage($e));
                            }
                        }
                    } else {
                        $errmsg = "Incorrect password. Details were not updated.";
                        Logger::log($errmsg); return new Error_View(ChlogErr::EC_USERBADPWD, $errmsg);   
                    }
                    break;
                
                case "removeuser":
                    $pwd = safeget::kvp($fields, "password", null, false);
                    $usr = safeget::session("user", null, null, false);
                    
                    if ($pwd) {
                        try {
                            //Remove the user from the database
                            User::removeUserRecord($usr->email, $pwd);
                                
                            //Remove the session user object
                            unset($_SESSION["user"]);

                            //remove cookie data from database
                            $cookie = (isset($_COOKIE["chlrm"])) ? $_COOKIE["chlrm"] : null;
                            Security::removeSessionCookie($cookie, $_SERVER["HTTP_USER_AGENT"]);

                            //remove cookie from browser
                            setcookie("chlrm", "", time()-3600, "/");

                            //display login view
                            $vw = new Redirect_View("/login/");                    
                            return $vw;
                            
                        } catch(\Exception $e) {
                            $errmsg = "Did not remove user (".$e->getMessage().")";
                            Logger::log($errmsg); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }
                    } else {
                        $errmsg = "Did not remove user - no password supplied.";
                        Logger::log($errmsg); 
                        return new Error_View(ChlogErr::EC_REMOVEUSERBADPWD, ChlogErr::EM_REMOVEUSERBADPWD);
                    }
                    break;
                
                default:
                    return new Useradmin_View(safeget::session("user", null, null, false));
                    break;
            }
            
        }
        
    }
        
        