<?php

    namespace chlog;

    class User {
        
        protected $email     = "";
        protected $nickname  = "";
        protected $isadmin   = 0;
        protected $isactive  = 0;
        protected $biography = "";
        protected $joindate  = null;
        protected $dbconn    = null;
        protected $isdirty   = false;
        
    /*  ============================================
        FUNCTION:   __construct 
        PARAMS:     eml - email address
                    nnm - nickname
                    adm - is admin
        RETURNS:    boolean
        ============================================  */
        public function __construct($eml="invalid", $nnm="unnamed", $adm=0, $act=1, $bio="", $jdt=null) {
            $this->email = $eml;
            $this->nickname = $nnm;
            $this->isadmin = $adm;
            $this->isactive = $act;
            $this->biography = $bio;
            $this->joindate = $jdt;
        }

        
    /*  Simple GET methods */
        public function Email()     { return $this->email; }
        public function Nickname()  { return $this->nickname; }
        public function Biography() { return $this->biography; }
        public function JoinDate()  { return $this->joindate; }
        public function IsAdmin()   { return $this->isadmin; }
        public function IsActive()  { return $this->isactive; }
        public function IsDirty()   { return $this->isdirty; }
        public function DBConn()    { return is_null($this->dbconn) ? Database::connect() : $this->dbconn; }

        
    /*  Simple SET methods */
        public function setEmail($eml)       { $this->email = $eml;     $this->isdirty = true; return $this; }
        public function setNickName($nnm)    { $this->nickname = $nnm;  $this->isdirty = true; return $this; }
        public function setBiography($bio)   { $this->biography = $bio; $this->isdirty = true; return $this; }
        public function setDBConn(\PDO $dbc) { $this->dbconn = $dbc;    return $this; }
        public function disconnectDB()       { $this->dbconn = null;    return $this; }
            
        
    /*  ============================================
        FUNCTION:   setPassword 
        PARAMS:     pwd - old password
                    npw - new password
                    np2 - new password check
        RETURNS:    (this) allows for method chaining
        ============================================  */
        public function setPassword($pwd, $npw=null, $np2=null) {
        
            //Default the new password fields to the current password if the 
            //primary new password field is null
            if (is_null($npw)) {
                $errmsg = "Error changing password - supplied password was null";
                Logger::log($errmsg); throw new \Exception ($errmsg); 
            } else {
                //check passwords match
                if ($npw != $np2) {
                    $errmsg = "Error changing password - supplied passwords did not match";
                    Logger::log($errmsg); throw new \Exception ($errmsg); 
                } else {
                    //Update the password in the database. Update will be rejected
                    //if the old password doesn't match the supplied parameter
                    try {
                        $sql = "CALL updateUserPassword(:eml, :pwd, :npw)";
                        $qry = $this->DBConn()->prepare($sql);
                        $qry->bindValue(":eml", $this->email);
                        $qry->bindValue(":pwd", $pwd);
                        $qry->bindValue(":npw", $npw);
                        $qSuccess = $qry->execute(); 

                        //rowcount = 1 if the update worked properly
                        if ($qSuccess) {
                            if ($qry->rowCount() == 1) {
                                $errmsg = "Updated password for ".$this->email;
                                Logger::log($errmsg); return true;   
                            } elseif ($qry->rowCount() > 1) {
                                $errmsg = "More than one user record updated. Looks suspicious. ";
                                Logger::log($errmsg); throw new \Exception($errmsg);
                            } else { 
                                $errmsg = "Failed to update password for ".$this->email." - 0 rows updated";
                                Logger::log($errmsg, "rowcount: ".$qry->rowCount()); throw new \Exception($errmsg);
                            }
                        } else {
                            $errmsg = "Failed to update password for ".$this->email." - query failed";
                            Logger::log($errmsg, "rowcount: ".$qry->rowCount()); throw new \Exception($errmsg);
                        }
                    } 
                    catch (\Exception $e) {
                        $errmsg = "Failed to update password for ".$this->email." - query exception";
                        Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                    }
                }
            }
        }


    /*  ============================================
        FUNCTION:   flushToDB
        PARAMS:     pwd - user password
        RETURNS:    (boolean) indicates if a DB update took place
        ============================================  */
        public function flushToDB($pwd=null) {
            if ($pwd && $this->isdirty) {
                try {
                    $qSuccess = user::updateUser($this->email, 
                                     $this->nickname, 
                                     $this->biography, 
                                     $pwd, null, null, 
                                     $this->DBConn());   
                    
                    $this->isdirty = false;
                    return $qSuccess;
                    
                } catch (\Exception $e) {
                    $errmsg = "Error flushing to DB";
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
            } else {
                return false;   
            }
        }
        
        
/*  ============================================
//  >>> STATIC METHODS <<<        
/*  ============================================
        
        
    /*  ============================================
        FUNCTION:   getUserFromEmail (STATIC)
        PARAMS:     eml - user email address
                    pwd - user password
                    dbc - database connection object
        RETURNS:    User object
        PURPOSE:    Constructs a user object from an email address
                    and returns a complete user object,
                    after validating password with back end.
        ============================================  */
        public static function getUserFromEmail($eml, $pwd, \PDO $dbc=null) {
    
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();
                        
            try {
                $sql = "CALL getUserFromEmail(:eml, :pwd)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":pwd", $pwd);
                $qry->execute();
                
                $userdata = $qry->fetch(\PDO::FETCH_ASSOC);

                if ($userdata) {
                    $user = new User($userdata["email"], 
                                     $userdata["nickname"], 
                                     $userdata["isadmin"],
                                     $userdata["active"],
                                     $userdata["biography"],
                                     $userdata["joindate"]);
                    return $user;   
                } else { 
                    $errmsg = "Failed to retrieve user record " . $eml;
                    Logger::log($errmsg); throw new \Exception($errmsg);
                }
            } 
            catch (\PDOException $e) {
                $errmsg = "unable to retrieve user record " . $eml;
                Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
            }
        }
        
        
        
    /*  ============================================
        FUNCTION:   updateUser (STATIC)
        PARAMS:     eml - email
                    nnm - nickname
                    bio - biography
                    pwd - old password
                    nwd - new password
                    np2 - new password check
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether the update worked or not
        ============================================  */
        public static function updateUser($eml, $nnm, $bio, $pwd, $npw=null, $np2=null, \PDO $dbc=null) {
        
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
                $errmsg = "error changing passwords - supplied passwords did not match (".$eml.")";
                Logger::log($errmsg); throw new \Exception($errmsg); 
            } else {
                
                //update user details
                try {
                    $sql = "CALL updateUser(:eml, :nnm, :bio, :pwd, :npw)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":nnm", $nnm);
                    $qry->bindValue(":bio", $bio);
                    $qry->bindValue(":pwd", $pwd);
                    $qry->bindValue(":npw", $npw);
                    $qSuccess = $qry->execute(); 
                    
                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        if ($qry->rowCount() == 1) {
                            $errmsg = "Updated user details for " . $eml;
                            Logger::log($errmsg); return true;   
                        } elseif ($qry->rowCount() > 1) {
                            $errmsg = "More than one user record updated. Looks suspicious. " . $eml;
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        } elseif ($qry->rowCount() == 0) {
                            $errmsg = "No changes to update for " . $eml . " - 0 rows updated";
                            Logger::log($errmsg); return false;
                        }
                    } else { 
                        $errmsg = "Failed to update user details for ".$eml.". User may not exist in database";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Unable to update user ".$eml;
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
            }
        }

    }