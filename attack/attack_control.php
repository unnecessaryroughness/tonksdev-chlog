<?php

    namespace chlog;

    class Attack_Control extends ChlogController {

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

            $usr = safeget::session("user", null, null, false);
            $eml = $usr->email;
            $dbc = Database::connect();
            
            switch ($type) {
                
                case "update":
                    $aid = safeget::kvp($fields, "txtID", null, false);
                    $ast = safeget::kvp($fields, "dtp1_txtFullDateTime", null, false);
                    $aen = safeget::kvp($fields, "dtp2_txtFullDateTime", null, false);
                    $alv = safeget::kvp($fields, "rngLevel", null, false);
                    $awv = safeget::kvp($fields, "txtWave", null, false);

                    //new record
                    if (isset($usr) && strlen($eml) > 0 ) {
                        
                        if (strlen($aid) == 0) {
                            //add a new record
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
                                    chlogErr::processRowCount("New Attack", $qry->rowCount(),
                                        ChlogErr::EM_ATTACKADDFAILED, ChlogErr::EC_ATTACKADDFAILED, true);          
                                } else {
                                    $errmsg = "Failed to add new attack - query failed";
                                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                    throw new \Exception(ChlogErr::EM_ATTACKADDFAILED, ChlogErr::EC_ATTACKADDFAILED);
                                }

                            } catch (\Exception $e) {
                                Logger::log(getNiceErrorMessage($e), $usr->email); 
                                return new Error_View($e->getCode(), getNiceErrorMessage($e));
                            }
                        } else {

                            //update a record
                            try {
                                $sql = "CALL updateAttack (:eml, :aid, :ast, :aen, :alv, :awv)";
                                $qry = $dbc->prepare($sql);
                                $qry->bindValue(":eml", $eml);
                                $qry->bindValue(":aid", $aid);
                                $qry->bindValue(":ast", $ast);
                                $qry->bindValue(":aen", $aen);
                                $qry->bindValue(":alv", $alv);
                                $qry->bindValue(":awv", $awv);
                                $qSuccess = $qry->execute(); 

                                if ($qSuccess) {
                                    chlogErr::processRowCount("Updated Attack ".$aid, $qry->rowCount(),
                                        ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED, false);          
                                } else {
                                    $errmsg = "Failed to update attack ".$aid." - query failed";
                                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                    throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
                                }

                            } catch (\Exception $e) {
                                Logger::log(getNiceErrorMessage($e), $usr->email); 
                                return new Error_View($e->getCode(), getNiceErrorMessage($e));
                            }

                        }
                    } else {
                        //no user email
                        $errmsg = "Failed to add/amend attack - no email address";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_ATTACKADDNOUSER, ChlogErr::EC_ATTACKADDNOUSER);
                    } 

                    //If we got here, it worked! so redirect to the home page.
                    return new Redirect_View("/");
                    break;

                
                case "review":
                    //get Attack object based on id passed
                    $aid = safeget::kvp($fields, "id", null, false);

                    if ($aid && $eml && $dbc) {
                        try {
                            $attack = self::getAttack($aid, $eml, $dbc);                
                            $attack->attachSymptoms(self::getSymptoms($aid, $eml, $dbc));

                            $vw = new Attack_View($eml, $attack);
                            $vw->symptomlist = Lookups::getLookupList($eml, "Symptom", true);
                            $vw->triggerlist = Lookups::getLookupList($eml, "Chtrigger", true);
                            $vw->locationlist = Lookups::getLookupList($eml, "Location", true);
                            $vw->treatmentlist = Lookups::getLookupList($eml, "Treatment", true);                            
                            return $vw;

                        } catch (\Exception $e) {
                            Logger::log(getNiceErrorMessage($e), $usr->email); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }
                    } else {
                        //no user email
                        $errmsg = "Failed to add/amend attack - no email address";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_GETATTACKSNOUSER, ChlogErr::EC_GETATTACKSNOUSER);
                    }
                
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    $vw = new Attack_View($eml);
                    $vw->symptomlist = Lookups::getLookupList($eml, "Symptom", true);
                    $vw->triggerlist = Lookups::getLookupList($eml, "Chtrigger", true);
                    $vw->locationlist = Lookups::getLookupList($eml, "Location", true);
                    $vw->treatmentlist = Lookups::getLookupList($eml, "Treatment", true);
                    return $vw;
                    break;
            }
        }

        
        private function getAttack($aid, $eml, $dbc=null) {
            $dbc = $dbc ? : Database::connect();
            
            if ($eml && $aid) {
                //retrieve a record
                try {
                    $sql = "CALL getAttack (:eml, :aid)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qSuccess = $qry->execute(); 

                    if ($qSuccess) {
                        $aRec = $qry->fetch(\PDO::FETCH_ASSOC);
                        $attack = new Attack($aRec["id"], $aRec["useremail"], $aRec["start"],
                                     $aRec["end"], $aRec["level"], $aRec["waveid"]);

                        return $attack;
                        
                    } else {
                        $errmsg = "Failed to update attack ".$aid." - query failed";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                    }

                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $usr->email); 
                    throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                }
            }
        }
        
        private function getSymptoms($aid, $eml, $dbc=null) {
            $dbc = $dbc ? : Database::connect();
            
            if ($eml && $aid) {
                try {
                    $sql = "CALL getAttackSymptoms (:eml, :aid)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qSuccess = $qry->execute(); 

                    if ($qSuccess) {
                        $aRecs = $qry->fetchAll(\PDO::FETCH_ASSOC);
                        $aRtn = [];
                        
                        foreach ($aRecs as $aRec) {
                            $aRtn[] = new Symptom($aRec["symptomid"], $aRec["description"]);
                        }
                        
                        return $aRtn;
                        
                    } else {
                        $errmsg = "Failed to update attack ".$aid." - query failed";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                    }

                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $usr->email); 
                    throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                }
            }
        }
        
        
    }

