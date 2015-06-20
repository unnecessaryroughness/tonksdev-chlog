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
                            if ($usr->flushToDB($pwd)) {
                                if ($pw2 && $pw3) {
                                    if ($usr->setPassword ($pwd, $pw2, $pw3)) {   
                                        Logger::log("Changed password for user ".$usr->email);
                                        return new Useradmin_View($usr);
                                    } else {
                                        $errmsg = "Failed to change password for user ".$usr->email;
                                        Logger::log($errmsg); return new Error_View(-1, $errmsg);
                                    }
                                }
                            } else {
                                $errmsg = "Failed to update details for user ";
                                Logger::log($errmsg.$usr->email); 
                                return new Error_View(-1, $errmsg);
                            }
                        }
                    } else {
                        return new Error_View(-1, "Incorrect password. Details were not updated.");   
                    }
                
                    return new Useradmin_View($usr);
                    break;
                
                default:
                    return new Useradmin_View(safeget::session("user", null, null, false));
                    break;
            }
            
        }
        
    }
        
        