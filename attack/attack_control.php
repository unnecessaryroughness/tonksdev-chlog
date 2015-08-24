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

            if (parent::notLoggedIn()) {
                return parent::notLoggedIn();
            }

            $usr = safeget::session("user", null, null, false);
            $eml = $usr ? $usr->email : "";
            $dbc = Database::connect();
            
            switch ($type) {
                
                case "update":
                    $aid = safeget::kvp($fields, "txtID", null, false);
                    $ast = safeget::kvp($fields, "dtp1_txtFullDateTime", null, false);
                    $aen = safeget::kvp($fields, "dtp2_txtFullDateTime", null, false);
                    $alv = safeget::kvp($fields, "rngLevel", null, false);
                    $awv = safeget::kvp($fields, "txtWave", null, false);
                    $trg = safeget::kvp($fields, "chkChtrigger", null, false);
                    $loc = safeget::kvp($fields, "chkLocation", null, false);
                    $sym = safeget::kvp($fields, "chkSymptom", null, false);
                    $tre = safeget::kvp($fields, "txtTreSeq", null, false);
                
                    if (isset($usr) && strlen($eml) > 0 ) {
                        
                        try {
                            if (strlen($aid) == 0) {
                                //new record    
                                $aid = self::addAttack($eml, $ast, $aen, $alv, $awv, $dbc);
                                self::addLinkedData($eml, $aid, "chtrigger", $trg, $dbc);
                                self::addLinkedData($eml, $aid, "location", $loc, $dbc);
                                self::addLinkedData($eml, $aid, "symptom", $sym, $dbc);
                                self::addLinkedTreatment($eml, $aid, $tre, $fields, $dbc);
                                
                            } else {
                                //update record    
                                self::updateAttack($eml, $aid, $ast, $aen, $alv, $awv, $dbc);
                                self::addLinkedData($eml, $aid, "chtrigger", $trg, $dbc, true);
                                self::addLinkedData($eml, $aid, "location", $loc, $dbc, true);
                                self::addLinkedData($eml, $aid, "symptom", $sym, $dbc, true);
                                self::addLinkedTreatment($eml, $aid, $tre, $fields, $dbc, true);
                            }
                            
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

                
                case "review":
                    //get Attack object based on id passed
                    $aid = safeget::kvp($fields, "id", null, false);

                    if ($aid && $eml && $dbc) {
                        try {
                            $attack = self::getAttack($aid, $eml, $dbc);                
                            $attack->attachSymptoms(self::getLinkedData($aid, $eml, "symptom", $dbc));
                            $attack->attachTriggers(self::getLinkedData($aid, $eml, "trigger", $dbc));
                            $attack->attachLocations(self::getLinkedData($aid, $eml, "location", $dbc));
                            $attack->attachTreatments(self::getLinkedData($aid, $eml, "treatment", $dbc));
                            
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
                        Logger::log($errmsg); 
                        return new Error_View(ChlogErr::EC_GETATTACKSNOUSER, ChlogErr::EM_GETATTACKSNOUSER);
                    }
                
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    //create a new attack
                try {
                    $vw = new Attack_View($eml);
                    $vw->symptomlist = Lookups::getLookupList($eml, "Symptom", true);
                    $vw->triggerlist = Lookups::getLookupList($eml, "Chtrigger", true);
                    $vw->locationlist = Lookups::getLookupList($eml, "Location", true);
                    $vw->treatmentlist = Lookups::getLookupList($eml, "Treatment", true);
                    return $vw;
                } catch (\Exception $e) {
                    Logger::log($e->getMessage());
                    return new Error_View(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                }
                    break;
            }
        }

        
    /*  ============================================
        FUNCTION:   getAttack
        PARAMS:     aid         attack id
                    eml         email of logged in user
                    dbc         database connection
        RETURNS:    (object)    attack object
        PURPOSE:    gets the requested attack from the database.
                    uses the logged in user email to verify that 
                    we are not peeking at another user's data.
        ============================================  */
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
                    Logger::log(getNiceErrorMessage($e), $eml); 
                    throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                }
            }
        }
        
    /*  ============================================
        FUNCTION:   getLinkedData
        PARAMS:     aid         attack id
                    eml         email of logged in user
                    typ         the type of linked data to get
                    dbc         database connection
        RETURNS:    (array)     array of lookup objects (or lookuptreatment objects)
        PURPOSE:    gets the requested linked data from the database.
                    Linked data is lookup data associated with an attack.
                    uses the logged in user email to verify that 
                    we are not peeking at another user's data.
        ============================================  */
        private function getLinkedData($aid, $eml, $typ, $dbc=null) {
            $dbc = $dbc ? : Database::connect();
            
            if ($eml && $aid) {
                try {
                    $sql = "CALL getAttack".$typ."s (:eml, :aid)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qSuccess = $qry->execute(); 

                    if ($qSuccess) {
                        $aRecs = $qry->fetchAll(\PDO::FETCH_ASSOC);
                        $aRtn = [];
                        
                        foreach ($aRecs as $aRec) {
                            if ($typ != "treatment") {
                                $aRtn[] = new Lookup($aRec[$typ."id"], $aRec["description"]);
                            } else {
                                $nt = new LookupTreatment($aRec[$typ."id"], $aRec["description"]);
                                $nt->setTreatmentParams($aRec["preparation"], $aRec["dosage"], $aRec["administered"]);
                                $aRtn[] = $nt;
                            }
                        }
                        
                        return $aRtn;
                        
                    } else {
                        $errmsg = "Failed to get attack liked data ".$aid." ".$typ." - query failed";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                    }

                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $eml); 
                    throw new \Exception(ChlogErr::EM_GETATTACKFAILED, ChlogErr::EC_GETATTACKFAILED);
                }
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
        
    /*  ============================================
        FUNCTION:   updateAttack
        PARAMS:     eml         email of logged in user
                    ast         attack start date-time
                    aen         attack end date-time
                    alv         attack level
                    awv         attack wave
                    dbc         database connection
        RETURNS:    (object)    attack object
        PURPOSE:    updates an existing attack in the database.
                    uses the logged in user email to verify that 
                    we are not writing data to another user's account.
        ============================================  */
        private function updateAttack($eml, $aid, $ast, $aen, $alv, $awv, $dbc=null) {
            $dbc = $dbc ? : Database::connect();
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
                Logger::log(getNiceErrorMessage($e), $eml); 
                throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
            }
        }

    /*  ============================================
        FUNCTION:   addLinkedData
        PARAMS:     eml         email of logged in user
                    aid         attack id
                    typ         the type of linked data to get
                    arr         the array of linked data to add to the database
                    dbc         database connection
                    replace     indicates if we should remove all existing
                                linked data before adding these records
        RETURNS:    (none)   
        PURPOSE:    adds the requested linked data to the database.
                    Linked data is lookup data associated with an attack.
                    uses the logged in user email to verify that 
                    we are not writing data to another user's account.
        ============================================  */
        private function addLinkedData($eml, $aid, $typ, $arr, $dbc=null, $replace=false) {
            $dbc = $dbc ? : Database::connect();
            $qSuccess = true;
            
            if ($replace) {
                try {
                    $sql = "CALL removeAllAttackLinks (:eml, :aid, :typ)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qry->bindValue(":typ", $typ);
                    $qSuccess = $qry->execute(); 
                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $eml); 
                    throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);                
                }
            }
                
            try {
                if ($qSuccess) {
                    $sql = "CALL addAttackLink (:eml, :aid, :typ, :lid)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qry->bindValue(":typ", $typ);

                    foreach ($arr as $rec) {
                        $qry->bindValue(":lid", $rec);
                        $qSuccess = $qry->execute(); 

                        if ($qSuccess) {
                            chlogErr::processRowCount("Added ".$typ." link ".$rec." for attack ".$aid, $qry->rowCount(),
                                ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED, false);          
                        } else {
                            $errmsg = "Failed to add ".$typ." link for attack ".$aid." - query failed";
                            Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                            throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
                        }
                    }
                } else {
                    $errmsg = "Failed to remove ".$typ." links for attack ".$aid." - query failed";
                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                    throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
                }
            } catch (\Exception $e) {
                Logger::log(getNiceErrorMessage($e), $eml); 
                throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);                
            }
        }

        
    /*  ============================================
        FUNCTION:   addLinkedTreatment
        PARAMS:     eml         email of logged in user
                    aid         attack id
                    arr         the array of linked data to add to the database
                    fields      the fields array from the $_POST variable. needed to get 
                                the field values from the repeating group of treatments
                    dbc         database connection
                    replace     indicates if we should remove all existing
                                linked treatment data before adding these records
        RETURNS:    (none)   
        PURPOSE:    adds the requested linked treatment data to the database.
                    Linked data is lookup data associated with an attack.
                    uses the logged in user email to verify that 
                    we are not writing data to another user's account.
        ============================================  */
        private function addLinkedTreatment($eml, $aid, $arr, $fields, $dbc=null, $replace=false) {            
            $dbc = $dbc ? : Database::connect();
            $qSuccess = true;
            
            if ($replace) {
                try {
                    $sql = "CALL removeAllAttackTreatments (:eml, :aid)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);
                    $qSuccess = $qry->execute(); 
                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $eml); 
                    throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);                
                }
            }
                
            try {
                if ($qSuccess) {
                    $sql = "CALL addTreatmentLink (:eml, :aid, :tid, :prp, :dos, :adm)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":aid", $aid);

                    if ($arr) {
                        foreach ($arr as $rec) {
                            $tid = safeget::kvp($fields, "selTre_".$rec, "(none)", false);
                            $prp = safeget::kvp($fields, "selPre_".$rec, "(none)", false);
                            $dos = safeget::kvp($fields, "txtDos_".$rec, "(none)", false);
                            $adm = safeget::kvp($fields, "txtAdm_".$rec, "", false);

                            $qry->bindValue(":tid", $tid);
                            $qry->bindValue(":prp", $prp);
                            $qry->bindValue(":dos", $dos);
                            $qry->bindValue(":adm", $adm);
                            $qSuccess = $qry->execute(); 

                            if ($qSuccess) {
                                chlogErr::processRowCount("Added treatment link ".$rec." for attack ".$aid, $qry->rowCount(),
                                    ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED, false);          
                            } else {
                                $errmsg = "Failed to add treatment link for attack ".$aid." - query failed";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
                            }
                        }
                    }
                } else {
                    $errmsg = "Failed to remove old treatment links for attack ".$aid." - query failed";
                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                    throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);
                }
            } catch (\Exception $e) {
                Logger::log(getNiceErrorMessage($e), $eml); 
                throw new \Exception(ChlogErr::EM_ATTACKUPDFAILED, ChlogErr::EC_ATTACKUPDFAILED);                
            }
        }


        
        
    }

