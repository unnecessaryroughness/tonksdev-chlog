<?php

    namespace chlog;

    class Treatmentplan_Control extends ChlogController {
 
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
            
            $dbc = Database::connect();
            $usr = safeget::session("user", null, null, false);
            if (isset($usr)) {
                $eml = $usr->email;
            }
                
                
            switch ($type) {
                case "update": 
                    //easiest way to refresh the plan is to remove everything and 
                    //recreate all of the records. Avoids having to figure out
                    //which records to add/update/remove.

                    //1. remove all usertreatmentplan records for this email address
                    
                    
                    //2. recreate records based on the JSO returned from the view
                    $jso = json_decode(safeget::post("hidJSO"), true);
                    $tre = $jso["treatments"];
                    
                    //iterate over every treatment
                    foreach ($tre as $tkey => $tval) {
                        
                        //same id and name for every dosage of this treatment
                        $upid = $tval["id"];
                        $upnm = $tval["name"];
                        
                        //iterate over every dosage of this treatment
                        foreach ($tval["doses"] as $dkey => $dval) {
                            
                            //get the variables for this treatment
                            $updf = $dval["dfrom"];
                            $updt = $dval["dto"];
                            $upun = $dval["units"];
                            $upds = $dval["dosage"];
                            $upxd = $dval["timesperday"];

                            //if all variables have a value, go ahead and update
                            if ($upid && $upnm && $updf && $updt && $upun && $upds && $upxd) {
                                Logger::log("id: {$upid}; name: {$upnm}; dfrom:{$updf}; dto:{$updt}; units:{$upun}; dosage:{$upds}; perday: {$upxd}");
                                
                                
                                //2, 
                            }

                        }
                    }
                    
                    //return new Redirect_View("/treatmentplan/");
                    break;
                    
                default:
                    try {
                        $planobj = self::getPlan($eml, $dbc);
                        return new Treatmentplan_View($planobj);
                    } catch (\Exception $e) {
                        Logger::log(getNiceErrorMessage($e), $eml); 
                        return new Error_View($e->getCode(), getNiceErrorMessage($e));
                    }
                    
                    break;
            }
            
        }
        
        
    /*  ============================================
        FUNCTION:   getPlan
        PARAMS:     eml    - email address of the user
        RETURNS:    (string) JSON data
        PURPOSE:    returns the plan data for the user in JSON format
        ============================================  */
        private function getPlan($eml, $dbc) {
            
            if (isset($eml) && strlen($eml) > 0) {
                
                try {
                    $sql = "CALL getMyTreatmentPlan (:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qSuccess = $qry->execute(); 

                    if ($qSuccess) {
                        $aRecs = $qry->fetchall(\PDO::FETCH_ASSOC);
                        return $aRecs;
                    } else {
                        $errmsg = "Failed to retrieve treatment plan - query failed";
                        Logger::log($errmsg, "rowcount: ".$qry->rowCount()); 
                        throw new \Exception(ChlogErr::EM_GETMYATTACKSFAILED, ChlogErr::EC_GETMYATTACKSFAILED);
                    }
                    
                } catch (\Exception $e) {
                    Logger::log(getNiceErrorMessage($e), $eml); 
                    return new Error_View($e->getCode(), getNiceErrorMessage($e));
                }
                
            }
        }
        
        
    }
        
        