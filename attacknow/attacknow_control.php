<?php

    namespace chlog;

    class AttackNow_Control extends ChlogController {

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
                
                default:
                    $td = new \DateTime();
                    $ast = $td->format("Y-m-d H:i");
                    $aen = null;
                    $alv = 1;
                    $awv = 1;
                
                    if (isset($usr) && strlen($eml) > 0 ) {
                        
                        try {
                            //new record    
                            $aid = self::addAttack($eml, $ast, $aen, $alv, $awv, $dbc);
                            return new AttackNow_View($ast);
                            
                        } catch (\Exception $e) {
                            Logger::log(getNiceErrorMessage($e), $usr->email); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));                                   
                        }
                    } else {
                        //no user email
                        $errmsg = "Failed to add/amend attack - no email address";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        return new Error_View(ChlogErr::EC_ATTACKADDNOUSER, ChlogErr::EM_ATTACKADDNOUSER);
                    } 

                    //If we got here, it worked! so redirect to the home page.
                    return new Redirect_View("/");
                    break;

            }
        }

        
    /*  ============================================
        FUNCTION:   addAttack
        PARAMS:     eml         email of logged in user
                    ast         attack start date-time
                    aen         attack end date-time
                    alv         attack level
                    awv         attack wave
                    dbc         database connection
        RETURNS:    (object)    attack object
        PURPOSE:    adds a new attack to the database.
                    uses the logged in user email to verify that 
                    we are not writing data to another user's account.
        ============================================  */
        private function addAttack($eml, $ast, $aen, $alv, $awv, $dbc=null) {
            $dbc = $dbc ? : Database::connect();
            try {
                $sql = "CALL addAttack (:eml, :ast, :aen, :alv, :awv)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":ast", $ast);
                $qry->bindValue(":aen", $aen);
                $qry->bindValue(":alv", $alv);
                $qry->bindValue(":awv", $awv);
                $qSuccess = $qry->execute(); 

                if ($qSuccess) {
                    $rtn = $qry->fetch(\PDO::FETCH_ASSOC);
                    $aid = $rtn["lastid"];
                    
                    chlogErr::processRowCount("New Attack", $qry->rowCount(),
                        ChlogErr::EM_ATTACKADDFAILED, ChlogErr::EC_ATTACKADDFAILED, true);          
                    
                    return $aid;
                    
                } else {
                    $errmsg = "Failed to add new attack - query failed";
                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                    throw new \Exception(ChlogErr::EM_ATTACKADDFAILED, ChlogErr::EC_ATTACKADDFAILED);
                }

            } catch (\Exception $e) {
                Logger::log(getNiceErrorMessage($e), $eml); 
                throw new \Exception(ChlogErr::EM_ATTACKADDFAILED, ChlogErr::EC_ATTACKADDFAILED);
            }
        }
        


        
        
    }

