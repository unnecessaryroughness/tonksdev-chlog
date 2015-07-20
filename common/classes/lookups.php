<?php 
    
    namespace chlog;

    class Lookups {

        protected $symptoms = null;
        protected $triggers = null;
        protected $sideeffects = null;
        protected $treatments = null;
        protected $waves = null;
        
    /*  ============================================
        FUNCTION:   __construct 
        PARAMS:     none
        RETURNS:    (object)
        ============================================  */
        public function __construct() {}

        
    /*  ============================================
        FUNCTION:   getSymptomsList 
        PARAMS:     eml     email address of user 
        RETURNS:    (object)
        PURPOSE:    returns a symptom list object tailored to this user
        ============================================  */
        public static function getSymptomsList($eml, \PDO $dbc=null) {
            
            //check user requested is currently logged in user
            $loggedinuser = safeget::session("user", "email", null);
                                             
            if ($eml == $loggedinuser) {
            
                //connect to database 
                $dbc = ($dbc) ? : Database::connect();

                //get symptoms list for user
                try {
                    $sql = "CALL getSymptomsForUser(:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->execute(); 
                    $symdata = $qry->fetchall(\PDO::FETCH_ASSOC);
                    
                } catch (\PDOException $e) {
                    Logger::log("Error retrieving symptoms for user");
                    throw new \Exception(EM_MISCDATAERR, EC_MISCDATAERR);
                }
                
                //create symptoms list object
                if ($symdata) {
                    $rtnlist = new SymptomList();
                    
                    //iterate data results set adding symptoms to the list  
                    foreach ($symdata as $sym) {
                        $rtnlist->addSymptom($sym["id"], $sym["description"],
                                            $sym["sort"], $sym["hidden"], $sym["defaultsort"]);   
                    }
                }
                
                //return symptom list object
                return $rtnlist;
                
            } else {
                Logger::log("User ".$loggedinuser." attempted to access data for ".$eml);
                throw new \Exception(chlogErr::EM_MISMATCHEDUSER, chlogErr::EC_MISMATCHEDUSER);
            }
        }

        
    /*  ============================================
        FUNCTION:   updateSymptomsList 
        PARAMS:     eml     email address of the user
                    lst     symptoms list object 
        RETURNS:    (boolean)
        PURPOSE:    Updates the SQL database based on the data
                    in the supplied symptoms list
        ============================================  */
        public static function updateSymptomsList($eml=null, $lst=null, \PDO $dbc=null) {
         
            //check user requested is currently logged in user
            $loggedinuser = safeget::session("user", "email", null);
                                             
            if ($eml == $loggedinuser) {
            
                //connect to database 
                $dbc = ($dbc) ? : Database::connect();

                //loop around the supplied list
                foreach ($lst as $symptom) {
                 
                    //check if description has changed
                    if ($symptom->descriptionhaschanged) {
                        
                        //update description
                        try {
                            $sql = "CALL updateSymptomDesc(:sid, :dsc, :ndsc)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":sid", $symptom->symptomid);
                            $qry->bindValue(":dsc", $symptom->originaldescription);
                            $qry->bindValue(":ndsc", $symptom->description);
                            $qSuccess = $qry->execute(); 

                            //rowcount = 1 if the update worked properly
                            if ($qSuccess) {
                                if ($qry->rowCount() == 1) {
                                    $errmsg = "Updated description for ".$symptom->description;
                                    Logger::log($errmsg);    
                                } elseif ($qry->rowCount() > 1) {
                                    $errmsg = "More than one symptom record updated. Looks suspicious. ";
                                    Logger::log($errmsg); 
                                    throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                                } else { 
                                    $errmsg = "Failed to update description for ".$symptom->description." - 0 rows updated";
                                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                    throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                                }
                            } else {
                                $errmsg = "Failed to update description for ".$symptom->description." - query failed";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            }
                            
                        } catch (\PDOException $e) {
                            Logger::log("Error updating symptom description.", $e->getMessage());
                            throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                        }
                    } else {
                        //description has not changed
                    }
                    
                    //update/add this symptom mapping for this user
                    // (only if this symptom with a sort value < 1000)
                    if ($symptom->isdirty) {
                        
                        //if a symptom sort order of >= 1000 (default) is found and the record 
                        //is hidden, set the sort order to max+1 so a link record will be created.
                        if ($symptom->sortorder >= 1000 && $symptom->hidden) {
                            $symptom->update($lst->getMaxSortOrder() + 1);
                        }
                        
                        try {
                            $sql = "CALL addUpdateUserSymptom(:sid, :eml, :srt, :hid)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":sid", $symptom->symptomid);
                            $qry->bindValue(":eml", $eml);
                            $qry->bindValue(":srt", $symptom->sortorder);
                            $qry->bindValue(":hid", $symptom->hidden);
                            $qSuccess = $qry->execute(); 
                            
                            if ($qSuccess) {
                                if ($qry->rowCount() == 1) {
                                    $errmsg = "Updated usersymptom for ".$eml;
                                    Logger::log($errmsg);    
                                } elseif ($qry->rowCount() > 1) {
                                    $errmsg = "More than one usersymptom record updated. Looks suspicious. ";
                                    Logger::log($errmsg); 
                                    throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                                } else { 
                                    $errmsg = "Failed to update usersymptom for ".$symptom->description." - 0 rows updated";
                                    Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                    throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                                }
                            } else {
                                $errmsg = "Failed to update usersymptom for ".$symptom->symptomid." - query failed";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            }
                        } catch (\PDOException $e) {
                            Logger::log("Error updating user-symptom link for ".$eml, $e->getMessage());
                            throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                        }
                    }
                }
            }
        }   

    }