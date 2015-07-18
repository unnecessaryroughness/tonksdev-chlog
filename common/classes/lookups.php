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
        
        // TEMPORARILY DISABLED !!!!!!!!!!!    
            $loggedinuser = safeget::session("user", "email", "marktonks75@gmail.com");
                                             
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
        
        // TEMPORARILY DISABLED !!!!!!!!!!!    
            $loggedinuser = safeget::session("user", "email", "marktonks75@gmail.com");
                                             
            if ($eml == $loggedinuser) {
            
                //connect to database 
                $dbc = ($dbc) ? : Database::connect();

                //loop around the supplied list
                foreach ($lst as $symptom) {
                 
                    //check if description has changed
                    if ($lst->descriptionhaschanged) {
                        
                        //update description
                    }
                    
                    //remove all symptom mappings for this user
                    
                    
                    //create all new symptom mappings for this user 
                    // (only if this symptom with a sort value < 1000)
                    
                    
                }
            }
        }
        
    }
