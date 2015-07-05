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

        public function DBConn()    { return is_null($this->dbconn) ? Database::connect() : $this->dbconn; }

    /*  ============================================
        FUNCTION:   __get
        PARAMS:     field - the read only field required
        RETURNS:    (variable)
        PURPOSE:    General purpose ReadOnly property getter
        ============================================  */
        public function __get( $field ) {
            switch( $field ) {
              case 'email':
                return $this->email;
              case 'nickname':
                return $this->nickname;
              case 'biography':
                return $this->biography;
              case 'isadmin':
                return $this->isadmin;
              case 'isactive':
                return $this->isactive;
              case 'isdirty':
                return $this->isdirty;
              case 'joindate':
                return $this->joindate;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
        
        
        
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
        PURPOSE:    Validates input data and immediately changes password on back end. 
                    Password is never stored in session before, during, or after this process.
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
                    Logger::log($errmsg); 
                    throw new \Exception ($errmsg, ChlogErr::EC_USERPWDSNOTMATCHED); 
                } else {
                    //Update the password in the database. Update will be rejected
                    //if the old password doesn't match the supplied parameter
                    try {
                        
                        if (Self::verifyPW($this->email, $pwd)) { 

                            $sql = "CALL updateUserPassword(:eml, :npw)";
                            $qry = $this->DBConn()->prepare($sql);
                            $qry->bindValue(":eml", $this->email);
                            $qry->bindValue(":npw", Security::chlogHash($npw));
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
                        } else {
                            //wrong password supplied    
                            $errmsg = "Failed to update password for ".$this->email." - wrong current password";
                            Logger::log($errmsg); throw new \Exception($errmsg);
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
        PURPOSE:    Flushes cached data about the nickname and biography to the back end.
                    Password must be supplied to do this. Password cannot be changed by this method.
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
                    $errmsg = "Error flushing to DB (".$e->getCode().")";
                    Logger::log($errmsg, $e->getMessage()); 
                    throw new \Exception($errmsg, $e->getCode());
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
                
                if (self::verifyPW($eml, $pwd)) {
                    
                    $sql = "CALL getUserFromEmail(:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->execute();

                    $userdata = $qry->fetch(\PDO::FETCH_ASSOC);

                    if ($userdata) {
                        if ($userdata["active"] == 1) {
                            $user = new User($userdata["email"], 
                                             $userdata["nickname"], 
                                             $userdata["isadmin"],
                                             $userdata["active"],
                                             $userdata["biography"],
                                             $userdata["joindate"]);
                            return $user;   
                        } else {
                            $errmsg = "User ".$eml." has not been activated.";
                            Logger::log($errmsg); throw new \Exception($errmsg, ChlogErr::EC_USERNOTACTIVE);
                        }
                    } else { 
                        $errmsg = "Failed to retrieve user record " . $eml;
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } else {
                    $errmsg = "Failed to retrieve user record ".$eml. " (incorrect current password).";
                    Logger::log($errmsg); throw new \Exception($errmsg, ChlogErr::EC_USERBADPWD);
                }
            } 
            catch (\PDOException $e) {
                $errmsg = "Unable to retrieve user record ".$eml;
                Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
            }
        }
        

    /*  ============================================
        FUNCTION:   getUserFromSession (STATIC)
        PARAMS:     eml - user email address
                    ser - series
                    tok - token
                    fpt - fingerprint
                    dbc - database object
        RETURNS:    User object
        PURPOSE:    Constructs a user object from an email address
                    and session details and returns a complete user object,
                    after validating password with back end.
        ============================================  */
        public static function getUserFromSession($eml, $ser, $tok, $fpt, \PDO $dbc=null) {
    
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();
                        
            try {
                $sql = "CALL getUserFromSession(:eml, :ser, :tok, :fpt)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":ser", $ser);
                $qry->bindValue(":tok", $tok);
                $qry->bindValue(":fpt", $fpt);
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
        PURPOSE:    Updates all updateable fields for the user. 
                    Current valid password must be supplied, but new password is optional.
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
                Logger::log($errmsg); throw new \Exception($errmsg, ChlogErr::EC_USERPWDSNOTMATCHED); 
            } else {
                
                //update user details
                try {
                    
                    if (self::verifyPW($eml, $pwd)) {

                        $sql = "CALL updateUser(:eml, :nnm, :bio, :npw)";
                        $qry = $dbc->prepare($sql);
                        $qry->bindValue(":eml", $eml);
                        $qry->bindValue(":nnm", $nnm);
                        $qry->bindValue(":bio", $bio);
                        $qry->bindValue(":npw", Security::chlogHash($npw));
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
                    } else {
                        $errmsg = "Failed to update user details for ".$eml.". Incorrect current password.";
                        Logger::log($errmsg); throw new \Exception($errmsg, ChlogErr::EC_USERBADPWD);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Unable to update user ".$eml;
                    Logger::log($errmsg." ".$e->getCode(), $e->getMessage()); 
                    throw new \Exception($errmsg, $e->getCode());
                }
            }
        }


    /*  ============================================
        FUNCTION:   registerUser (STATIC)
        PARAMS:     eml - email
                    nnm - nickname
                    bio - biography
                    npw - new password
                    np2 - new password check
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether the registration worked or not
        PURPOSE:    Adds all updateable fields for the new user. 
                    Validates that new passwords match before sending to back end.
        ============================================  */
        public static function registerUser($eml, $nnm, $bio, $npw, $np2, \PDO $dbc=null) {
         
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();
            
            //Get unique registration token for user
            $now = new \DateTime();
            $emlL4 = substr($eml, 0, 4);
            $emlR = substr($eml, 4);
            $tok = Security::chlogHash($emlL4.$now->format("dmYHis").$emlR);
            
            //check passwords match
            if ($npw != $np2) {
                $errmsg = "Error registering user - supplied passwords did not match (".$eml.")";
                Logger::log($errmsg); throw new \Exception($errmsg); 
            } else {
            
                //register user
                try {
                    $sql = "CALL registerUser(:eml, :nnm, :bio, :npw, :tok)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":nnm", $nnm);
                    $qry->bindValue(":bio", $bio);
                    $qry->bindValue(":npw", Security::chlogHash($npw));
                    $qry->bindValue(":tok", $tok);
                    $qSuccess = $qry->execute(); 
                    
                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        $errmsg = "Registered new user " . $eml;
                        Logger::log($errmsg); 
                        
                        //send email confirmation
                        $to = $eml;
                        $subject = "chLOG new user registration -- confirmation";
                        $body = self::getEmailBody($nnm, $tok);
                        $headers = "From: notify@tonksdev.co.uk\r\nReply-To: notify@tonksdev.co.uk\r\n";
                        
                        if (mail($to, $subject, $body, $headers)) {
                            $errmsg = "Sent email confirmation to ".$eml;
                            Logger::log($errmsg); return true;   
                        } else {
                            $errmsg = "Failed to send email confirmation to ".$eml;
                            Logger::log($errmsg); Logger::log($body); throw new \Exception($errmsg);
                        }
                    } else { 
                        $errmsg = "Failed to register user ".$eml;
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Unable to register user ".$eml;
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
            }
            
            //if all succeeded, return the newly registered user in a user object
            return self::getUserFromEmail($eml, $npw);
        }
        
        
    /*  ============================================
        FUNCTION:   getEmailBody (PROTECTED STATIC)
        PARAMS:     nnm - nickname of user
                    tok - token to append to url
        RETURNS:    (string) body text of email
        PURPOSE:    uses HEREDOC to return a formatted string email body
        ============================================  */
        protected static function getEmailBody($nnm, $tok) {
            return <<<EOT
Hello {$nnm}. Thank you for registering with chLOG.
Please click on the link below to activate your account.

http://chlog.tonksdev.co.uk/activate/?aid={$tok}

EOT;
        }

        
    /*  ============================================
        FUNCTION:   getRecoveryEmailBody (PROTECTED STATIC)
        PARAMS:     tok - token to append to url
        RETURNS:    (string) body text of email
        PURPOSE:    uses HEREDOC to return a formatted string email body
        ============================================  */
        protected static function getRecoveryEmailBody($tok) {
            return <<<EOT
Please click on the link below to recover your account.

http://chlog.tonksdev.co.uk/recoverpw/?rid={$tok}

EOT;
        }
        
        
    /*  ============================================
        FUNCTION:   setActive (STATIC)
        PARAMS:     tok - token from email
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether the activation worked or not
        PURPOSE:    Flips "active" flag on user record to TRUE if the supplied token
                    matches the one that was sent by email.
        ============================================  */
        public static function setActive($tok=null, \PDO $dbc=null) {

            //Do not proceed if the token was not supplied
            if ($tok) {
                //if the 'dbc' parameter was not supplied then connect to the 
                //default database using default parameters.
                $dbc = ($dbc) ? : Database::connect();

                //Update the active flag in the database. Update will only succeed if
                //a user record with a matching token is found.
                try {
                    
                    $sql = "CALL updateUserActive(:tok)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":tok", $tok);
                    $qSuccess = $qry->execute(); 

                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        if ($qry->rowCount() == 1) {
                            $errmsg = "Activated user";
                            Logger::log($errmsg); return true;   
                        } elseif ($qry->rowCount() > 1) {
                            $errmsg = "Activated more than one user. Looks suspicious";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        } else { 
                            $errmsg = "Failed to activate user - 0 rows updated";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        }
                    } else {
                        $errmsg = "Failed to activate user - query failed";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Failed to activate user - query exception";
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
                
            } else {
                //token was empty
                Logger::log("Attempted to activate a user with a null token.");
                return false;   
            }
            
            return true;   
        }
        
        
                
    /*  ============================================
        FUNCTION:   verifyPW (STATIC)
        PARAMS:     eml - user's email address
                    pwd - entered password 
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether the password is valid or not
        PURPOSE:    Verifies a supplied password against a user record in the database.
        ============================================  */
        public static function verifyPW($eml=null, $pwd=null, \PDO $dbc=null) {

            if ($eml && $pwd) {
                //if the 'dbc' parameter was not supplied then connect to the 
                //default database using default parameters.
                $dbc = ($dbc) ? : Database::connect();
                
                //Update the active flag in the database. Update will only succeed if
                //a user record with a matching token is found.
                try {
                    $sql = "CALL getHash(:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qSuccess = $qry->execute(); 
                    
                    if ($qSuccess) {
                        $userdata = $qry->fetch(\PDO::FETCH_ASSOC);
                        if (Security::chlogCheckHash($pwd, $userdata["hash"])) {
                            return true;   
                        } else {
                            Logger::log("didn't match password for ".$eml);
                            return false;
                        }
                    } else {
                        $errmsg = "Failed to verify password - query failed";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Failed to verify password - query exception";
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
            } else {
                return false;
            }
            
        }
        
    /*  ============================================
        FUNCTION:   setRecoveryMode (STATIC)
        PARAMS:     eml - email of user to set into recovery mode
                    dbc - database connection object
        RETURNS:    (boolean) indicates whether recovery mode was set or not
        PURPOSE:    Flips "inrecovery" flag on user record to TRUE 
        ============================================  */
        public static function setRecoveryMode($eml=null, \PDO $dbc=null) {

            //Do not proceed if the email was not supplied
            if ($eml) {
                //if the 'dbc' parameter was not supplied then connect to the 
                //default database using default parameters.
                $dbc = ($dbc) ? : Database::connect();

                //generate a random token to use for recovering the account
                $tok = Security::generateRandomToken();
                    
                //Update the active flag in the database. Update will only succeed if
                //a user record with a matching token is found.
                try {
                        
                    $sql = "CALL updateUserInRecovery(:eml, :tok)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":tok", $tok);
                    $qSuccess = $qry->execute(); 

                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        if ($qry->rowCount() == 1) {
                            $errmsg = "User ".$eml." now in recovery mode.";
                            Logger::log($errmsg); 
                            
                            //send email confirmation
                            $to = $eml;
                            $subject = "chLOG recover user account";
                            $body = self::getRecoveryEmailBody($tok);
                            $headers = "From: notify@tonksdev.co.uk\r\nReply-To: notify@tonksdev.co.uk\r\n";

                            if (mail($to, $subject, $body, $headers)) {
                                $errmsg = "Sent email confirmation to ".$eml;
                                Logger::log($errmsg); 
                                return true;   
                            } else {
                                $errmsg = "Failed to send recovery email to ".$eml;
                                Logger::log($errmsg); Logger::log($body); 
                                throw new \Exception($errmsg);
                            }
                            
                        } elseif ($qry->rowCount() > 1) {
                            $errmsg = "Placed more than one user into recovery mode. Looks suspicious";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        } else { 
                            $errmsg = "Failed to place user in recovery mode - 0 rows updated";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        }
                    } else {
                        $errmsg = "Failed to place user in recovery mode - query failed";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Failed to place user in recovery mode - query exception";
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
                
            } else {
                //email address was empty
                Logger::log("Attempted to put a user into recovery mode with a null email address.");
                return false;   
            }
        }
        

    /*  ============================================
        FUNCTION:   completeRecoveryMode (STATIC)
        PARAMS:     tok - token to remove from recovery mode
                    pwd - new password to set 
        RETURNS:    (boolean) indicates whether recovery mode was completed or not
        PURPOSE:    Flips "inrecovery" flag on user record to FALSE and
                    updates password to match supplied parameter
        ============================================  */
        public static function completeRecoveryMode($tok=null, $pwd=null, \PDO $dbc=null) {

            //Do not proceed if the email was not supplied
            if ($tok && $pwd) {
                //if the 'dbc' parameter was not supplied then connect to the 
                //default database using default parameters.
                $dbc = ($dbc) ? : Database::connect();

                //hash the new password
                $pwd = Security::chlogHash($pwd);
                    
                //Update the inrecovery flag in the database. Update will only succeed if
                //a user record with a matching token is found.
                try {
                        
                    $sql = "CALL updateUserCompleteRecovery(:tok, :pwd)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":tok", $tok);
                    $qry->bindValue(":pwd", $pwd);
                    $qSuccess = $qry->execute(); 

                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        if ($qry->rowCount() == 1) {
                            $errmsg = "User now out of recovery mode.";
                            Logger::log($errmsg); return true;
                        } elseif ($qry->rowCount() > 1) {
                            $errmsg = "Brought more than one user out of recovery mode. Looks suspicious";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        } else { 
                            $errmsg = "Failed to bring user out of recovery mode - 0 rows updated";
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        }
                    } else {
                        $errmsg = "Failed to bring user out of recovery mode - query failed";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } 
                catch (\Exception $e) {
                    $errmsg = "Failed to bring user out of recovery mode - ".$e->getMessage();
                    Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
                }
                
            } else {
                //email address was empty
                Logger::log("Attempted to bring a user out of recovery mode with a null token or password.");
                return false;   
            }
        }
        
    /*  ============================================
        FUNCTION:   removeUserRecord (STATIC)
        PARAMS:     eml - email address of user to remove
                    pwd - password of the user to remove  
                    dbc - database connection to use
        RETURNS:    (boolean) indicates whether user was removed or not
        PURPOSE:    Verifies the user password, then removes the
                    user record from the database.
        ============================================  */
        public static function removeUserRecord($eml=null, $pwd=null, \PDO $dbc=null) {

            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();

            try {
                if (self::verifyPW($eml, $pwd)) {

                    $sql = "CALL removeUser(:eml)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qSuccess = $qry->execute(); 

                    //rowcount = 1 if the update worked properly
                    if ($qSuccess) {
                        if ($qry->rowCount() == 1) {
                            $errmsg = "Removed user record " . $eml;
                            Logger::log($errmsg); return true;   
                        } elseif ($qry->rowCount() > 1) {
                            $errmsg = "Removed more than one user record!! Looks suspicious. " . $eml;
                            Logger::log($errmsg); throw new \Exception($errmsg);
                        } elseif ($qry->rowCount() == 0) {
                            $errmsg = "No user record to remove for " . $eml . " - 0 rows affected";
                            Logger::log($errmsg); return false;
                        }
                    } else { 
                        $errmsg = "Failed to remove user record for ".$eml.". User may not exist in database";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } else {
                    $errmsg = "Failed to remove user record for ".$eml.". Incorrect current password.";
                    Logger::log($errmsg); throw new \Exception($errmsg);
                }
            } catch (\Exception $e) {
                $errmsg = "Failed to remove user record - " . $e->getMessage();
                Logger::log($errmsg, $e->getMessage()); throw new \Exception($errmsg);
            }
        }
    }


