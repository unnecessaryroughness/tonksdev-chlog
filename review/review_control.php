<?php

    namespace chlog;

    class Review_Control extends ChlogController {

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

            if (parent::notLoggedIn()) {
                return parent::notLoggedIn();
            }
            
            switch ($type) {
                
                case "update":

                    return new Redirect_View("/");
                    break;
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                
                    //retrieve my records
                    $usr = safeget::session("user", null, null, false);
                    $dbc = Database::connect();

                    //new record
                    if (isset($usr)) {
                        $eml = $usr->email;

                        if (strlen($eml) > 0) {

                            //retrieve my records
                            try {
                                $sql = "CALL getMyAttacks (:eml)";
                                $qry = $dbc->prepare($sql);
                                $qry->bindValue(":eml", $eml);
                                $qSuccess = $qry->execute(); 

                                if ($qSuccess) {
                                    $aRecs = $qry->fetchall(\PDO::FETCH_ASSOC);
                                } else {
                                    $errmsg = "Failed to retrieve a list of my attacks - query failed";
                                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                    throw new \Exception(ChlogErr::EM_GETMYATTACKSFAILED, ChlogErr::EC_GETMYATTACKSFAILED);
                                }

                            } catch (\Exception $e) {
                                Logger::log(getNiceErrorMessage($e), $usr->email); 
                                return new Error_View($e->getCode(), getNiceErrorMessage($e));
                            }
                        } else {
                            //no user email
                            $errmsg = "Failed to retrieve list of my attacks - no email address";
                            Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                            return new Error_View(ChlogErr::EC_GETATTACKSNOUSER, ChlogErr::EM_GETATTACKSNOUSER);
                        } 
                    } else {
                        //no user object in session
                        $errmsg = "Failed to retrieve list of attacks - no user object";
                        Logger::log($errmsg); 
                        return new Error_View(ChlogErr::EC_GETATTACKSNOUSER, ChlogErr::EM_GETATTACKSNOUSER);
                    }
                    
                    //turn into a list of attack objects
                    $aList = [];
                
                    foreach ($aRecs as $aRec) {
                        $aList[] = new Attack($aRec["id"], $aRec["useremail"], $aRec["start"],
                                             $aRec["end"], $aRec["level"], $aRec["waveid"]);
                    }
                
                    //pass to the view
                    return new Review_View($aList);
                    break;
            }
        }

    }

