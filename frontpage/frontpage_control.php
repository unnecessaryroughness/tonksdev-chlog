<?php 

    namespace chlog;

    class Frontpage_Control extends ChlogController {

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
        PURPOSE:    returns the relevant HTML markup for display, from the view object
        ============================================  */
        public function process($type, $fields) {

            if (parent::notLoggedIn()) {
                return parent::notLoggedIn();
            }

            switch ($type) {

                default:
                    
                    //get stats for view
                    $usr = safeget::session("user", null, null, false);
                    $eml = $usr->email;
                    $dbc = Database::connect();
                    
                    if (strlen($eml) > 0) {
                    
                        //get attacks in 1 week
                        try {
                            $sql = "CALL getAttacksInPeriod (:eml, :int, :ityp)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":eml", $eml);
                            $qry->bindValue(":int", "1");
                            $qry->bindValue(":ityp", "WEEK");
                            $qSuccess = $qry->execute(); 

                            if ($qSuccess) {
                                $a1wRecs = $qry->fetchall(\PDO::FETCH_ASSOC);
                            } else {
                                $errmsg = "Failed to retrieve a list of my attacks this week for dashboard - query failed";
                                Logger::log($errmsg); 
                                throw new \Exception(ChlogErr::EM_GETMYATTACKSFAILED, ChlogErr::EC_GETMYATTACKSFAILED);
                            }

                        } catch (\Exception $e) {
                            Logger::log(getNiceErrorMessage($e), $usr->email); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }
                        
                        //get attacks in 1 month
                        try {
                            $sql = "CALL getAllAttacksInPeriod (:eml, :int, :ityp)";
                            $qry = $dbc->prepare($sql);
                            $qry->bindValue(":eml", $eml);
                            $qry->bindValue(":int", "1");
                            $qry->bindValue(":ityp", "MONTH");
                            $qSuccess = $qry->execute(); 

                            if ($qSuccess) {
                                $a1mRecs = $qry->fetchall(\PDO::FETCH_ASSOC);
                            } else {
                                $errmsg = "Failed to retrieve a list of my attacks this week for dashboard - query failed";
                                Logger::log($errmsg); 
                                throw new \Exception(ChlogErr::EM_GETMYATTACKSFAILED, ChlogErr::EC_GETMYATTACKSFAILED);
                            }

                        } catch (\Exception $e) {
                            Logger::log(getNiceErrorMessage($e), $usr->email); 
                            return new Error_View($e->getCode(), getNiceErrorMessage($e));
                        }

                    }
                
                
                    return new Frontpage_View($a1wRecs, $a1mRecs);    
                    break;
            }
        }
    }