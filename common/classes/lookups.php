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
                        $rtnlist->addSymptom($sym["nickname"], $sym["id"], $sym["description"],
                                            $sym["sort"], $sym["hidden"]);   
                    }
                }
                
                //return symptom list object
                return $rtnlist;
                
            } else {
                Logger::log("User ".$loggedinuser." attempted to access data for ".$eml);
                throw new \Exception(chlogErr::EM_MISMATCHEDUSER, chlogErr::EC_MISMATCHEDUSER);
            }
        }
        
    }
