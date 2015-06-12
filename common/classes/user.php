<?php

    namespace chlog;

    class User {
        
        protected $email;
        protected $nickname;
        protected $isadmin;
        protected $dbconn;
        
    /*  ============================================
        FUNCTION:   __construct 
        PARAMS:     eml - email address
                    nnm - nickname
                    adm - is admin
        RETURNS:    boolean
        ============================================  */
        public function __construct($eml="invalid", $nnm="unnamed", $adm=0) {
            $this->email = $eml;
            $this->nickname = $nnm;
            $this->isadmin = $adm;
        }

        
    /*  Simple GET methods */
        
        public function getEmail()    { return $this->email; }
        public function getNickname() { return $this->nickname; }
        public function getIsAdmin()  { return $this->isadmin; }
        
    /*  Simple SET methods */
        
        public function setEmail($eml) {
            $this->email = $eml;
            return $this;
        }

        public function setNickName($nnm) {
            $this->nickname = $nnm;
            return $this;
        }
        
        public function getDBConn() {
            return is_null($this->dbconn) ? Database::connect() : $this->dbconn;
        }
        
                
        
    /*  ============================================
        FUNCTION:   getUserFromEmail (STATIC)
        PARAMS:     eml - user email address
                    dbc - database connection object
        RETURNS:    User object
        PURPOSE:    Constructs a user object from an email address
                    and returns a complete user object 
        ============================================  */
        public static function getUserFromEmail($eml, \PDO $dbc=null) {
    
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();
                        
            try {
                $sql = "CALL getUserFromEmail(:eml)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->execute();
                
                $userdata = $qry->fetch(\PDO::FETCH_ASSOC);

                if ($userdata) {
                    $user = new User($userdata["email"], 
                                     $userdata["nickname"], 
                                     $userdata["isadmin"]);
                    return $user;   
                } else { 
                    return false;
                }
            } 
            catch (\PDOException $e) {
                Logger::log("unable to retrieve user record " . $eml, $e->getMessage());
                throw new \Exception('Unable to retrieve user ' . $eml);
            }
        }
        
    /*  ============================================
        FUNCTION:   setDBConn
        PARAMS:     dbc - database connection (PDO object)
        RETURNS:    boolean
        ============================================  */
        public function setDBConn(\PDO $dbc) {
            $this->dbconn = $dbc;
            return true;
        }
        
    /*  ============================================
        FUNCTION:   disconnectDB
        PARAMS:     none
        RETURNS:    boolean
        ============================================  */
        public function disconnectDB() {
            $this->dbconn = null; 
            return true;
        }
        
        
        
    /*  ============================================
        FUNCTION:   updateUser (STATIC)
        PARAMS:     eml - email
                    nnm - nickname
                    pwd - old password
                    nwd - new password
                    np2 - new password check
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether the update worked or not
        ============================================  */
        public static function updateUser($eml, $nnm, $pwd, $npw=null, $np2=null, \PDO $dbc=null) {
        
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();

            //Default the new password fields to the current password if the 
            //primary new password field is null
            if (is_null($npw)) {
                $npw = $pwd;
                $np2 = $pwd;
            }

            //check passwords match
            if ($npw != $np2) {
                Logger::log("error changing passwords - supplied passwords did not match (".$eml.")");
                throw new \Exception ("Supplied passwords did not match"); 
            } else {
                
                //update user details
                try {
                    $sql = "CALL updateUser(:eml, :nnm, :pwd, :npw)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":nnm", $nnm);
                    $qry->bindValue(":pwd", $pwd);
                    $qry->bindValue(":npw", $npw);
                    $qry->execute(); 
                    
                    //rowcount = 1 if the update worked properly
                    if ($qry->rowCount() == 1) {
                        Logger::log("Updated user details for " . $eml);
                        return true;   
                    } elseif ($qry->rowCount() > 1) {
                        Logger::log("More than one user record updated. Looks suspicious. " . $eml);
                        throw new \Exception('Error updating user ' . $eml);
                    } else { 
                        Logger::log("Failed to update user details for " . $eml, "rowcount: ".$qry->rowCount());
                        throw new \Exception('Unable to update user ' . $eml);
                    }
                } 
                catch (\Exception $e) {
                    Logger::log("Unable to update user ".$uid, $e->getMessage());
                    throw new \Exception('Unable to update user ' . $eml);
                }
            }
        }

    }