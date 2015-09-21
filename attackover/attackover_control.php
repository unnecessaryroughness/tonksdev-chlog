<?php

    namespace chlog;

    class AttackOver_Control extends ChlogController {

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

            $usr = safeget::session("user", null, null, false);
            $eml = $usr ? $usr->email : "";
            $dbc = Database::connect();
            
            switch ($type) {
                case "over": 
                default:
                    if (isset($usr) && strlen($eml) > 0 ) {
                        
                        try {
                            //get open attack record    
                            $sql = "CALL getMyOpenAttack (:eml)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":eml", $eml);
                            $qSuccess = $qry->execute(); 

                            if ($qSuccess) {
                                chlogErr::processRowCount("Open Attack", $qry->rowCount(),
                                    ChlogErr::EM_GT1OPENATTACK, ChlogErr::EC_GT1OPENATTACK, true, true);          
                                
                                $rtn = $qry->fetch(\PDO::FETCH_ASSOC);
                                $aid = $rtn["id"];
                                
                                return new Redirect_View("/attack/?id=".$aid);

                            } else {
                                $errmsg = "Failed to find open attack - query failed";
                                Logger::log($errmsg); 
                                throw new \Exception(ChlogErr::EM_GT1OPENATTACK, ChlogErr::EC_GT1OPENATTACK);
                            }
                        } catch (\Exception $e) {
                            Logger::log(getNiceErrorMessage($e), $usr->email); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));                                   
                        }
                    } else {
                        //no user email
                        $errmsg = "Failed to retrieve open attack - no email address";
                        Logger::log($errmsg); 
                        return new Error_View(ChlogErr::EC_ATTACKADDNOUSER, ChlogErr::EM_ATTACKADDNOUSER);
                    } 

                    //If we got here, it failed, so redirect to the home page.
                    return new Redirect_View("/");
                    break;
            }
        }

        
        
    }

