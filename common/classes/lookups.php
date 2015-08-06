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
        FUNCTION:   getLookupList 
        PARAMS:     eml     email address of user 
                    lku     lookup to get
                    dbc     database connection object 
        RETURNS:    (object)
        PURPOSE:    returns a lookup list object tailored to this user
        ============================================  */
        public static function getLookupList($eml=null, $lku=null, \PDO $dbc=null) {
            
            //check user requested is currently logged in user
            $loggedinuser = safeget::session("user", "email", null);
                                             
            if ($eml == $loggedinuser) {
            
                //connect to database 
                $dbc = ($dbc) ? : Database::connect();

                //get lookup list for user
                try {
                    $sql = "CALL get".$lku."sForUser(:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->execute(); 
                    $lkudata = $qry->fetchall(\PDO::FETCH_ASSOC);
                    
                } catch (\PDOException $e) {
                    Logger::log("Error retrieving ".$lku." for user");
                    throw new \Exception(ChlogErr::EM_MISCDATAERR, ChLogErr::EC_MISCDATAERR);
                }
                
                //create symptoms list object
                if ($lkudata) {
                    $rtnlist = new LookupList();
                    
                    //iterate data results set adding lookups to the list  
                    foreach ($lkudata as $lku) {
                        $rtnlist->addLookup($lku["id"], $lku["description"],
                                             $lku["sort"], $lku["hidden"], 
                                             $lku["defaultsort"], $lku["description"]);   
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
        FUNCTION:   updateLookupList 
        PARAMS:     eml     email address of the user
                    lku     lookup to update
                    lst     lookup list object 
        RETURNS:    (boolean)
        PURPOSE:    Updates the SQL database based on the data
                    in the supplied lookup list
        ============================================  */
        public static function updateLookupList($eml=null, $lku=null, $lst=null, \PDO $dbc=null) {
         
            //check user requested is currently logged in user
            $loggedinuser = safeget::session("user", "email", null);
                                             
            if ($eml == $loggedinuser) {
            
                //connect to database 
                $dbc = ($dbc) ? : Database::connect();

                //loop around the supplied list
                foreach ($lst as $record) {
                 
                    //check if description has changed
                    if ($record->descriptionhaschanged) {
                        
                        //update description
                        try {
                            $sql = "CALL update".$lku."Desc(:sid, :dsc, :ndsc)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":sid", $record->id);
                            $qry->bindValue(":dsc", $record->originaldescription);
                            $qry->bindValue(":ndsc", $record->description);
                            $qSuccess = $qry->execute(); 

                            //rowcount = 1 if the update worked properly
                            if ($qSuccess) {
                                chlogErr::processRowCount("Description", $qry->rowCount(), 
                                        ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            } else {
                                $errmsg = "Failed to update ".$lku." description for ".$record->description." - query failed";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            }
                            
                        } catch (\PDOException $e) {
                            Logger::log("Error updating ".$lku." description.", $e->getMessage());
                            throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                        }
                    }
                    
                    //update/add this record mapping for this user
                    if ($record->isdirty) {
                        
                        $sid = $record->id; 
                        
                        //if this symptom is new, add it to the database first.
                        if ($record->isnew) {
                            try {
                                $sql = "CALL add".$lku."(:des)";
                                $qry = $dbc->prepare($sql);
                                $qry->bindValue(":des", $record->description);
                                $qSuccess = $qry->execute(); 
                                $rtn = $qry->fetch(\PDO::FETCH_ASSOC);
                                $sid = $rtn["lastid"];
                                
                                chlogErr::processRowCount("New ".$lku, $qry->rowCount(), 
                                                ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                                
                            } catch (\Exception $e) {
                                Logger::log("Error adding new ".$lku." (".$symptom->description.")", $e->getMessage());
                                throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            }
                        }
                        
                        try {
                            $sql = "CALL addUpdateUser".$lku."(:sid, :eml, :srt, :hid)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":sid", $sid);
                            $qry->bindValue(":eml", $eml);
                            $qry->bindValue(":srt", $record->sortorder);
                            $qry->bindValue(":hid", $record->hidden);
                            $qSuccess = $qry->execute(); 

                            if ($qSuccess) {
                                chlogErr::processRowCount("User".$lku, $qry->rowCount(),
                                                    ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);          
                            } else {
                                $errmsg = "Failed to update user".$lku." for ".$record->id." - query failed";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                                throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                            }
                        } catch (\Exception $e) {
                            Logger::log("Error updating user-".$lku." link for ".$eml, $e->getMessage());
                            throw new \Exception(ChlogErr::EM_LOOKUPCHANGEFAILED, ChlogErr::EC_LOOKUPCHANGEFAILED);
                        }
                    }
                }
                
                //reset "isdirty" flags for all records in the list.
                $lst->setAllClean();
            } else {
                Logger::log("User attempted to update data for another user. ");
                throw new \Exception(chlogErr::EM_MISMATCHEDUSER, chlogErr::EC_MISMATCHEDUSER);
            }

        }   
        
        
    }