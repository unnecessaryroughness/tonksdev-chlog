<?php


/*
    REMAIN LOGGED IN NOTES...
    
    http://jaspan.com/improved_persistent_login_cookie_best_practice

    X. When the user successfully logs in with Remember Me checked, a login cookie is issued 
       in addition to the standard session management cookie.[2]
    X. The login cookie contains the user's username, a series identifier, and a token. The series 
       and token are unguessable random numbers from a suitably large space. All three are stored 
       together in a database table.
    X. When a non-logged-in user visits the site and presents a login cookie, the username, series,
       and token are looked up in the database.
    X. If the triplet is present, the user is considered authenticated. The used token is removed from 
       the database. A new token is generated, stored in database with the username and the same 
       series identifier, and a new login cookie containing all three is issued to the user.
    5. If the username and series are present but the token does not match, a theft is assumed. The 
       user receives a strongly worded warning and all of the user's remembered sessions are deleted.
    6. If the username and series are not present, the login cookie is ignored.

    It is critical that the series identifier be reused for each token in a series. If the series 
    identifier were instead simply another one time use random number, the system could not 
    differentiate between a series/token pair that had been stolen and one that, for example, had  
    simply expired and been erased from the database.
    
*/

    namespace chlog;

    class Security {

        const SALT = "1009700e1675853b167ea786dc4c3f35";
        
    /*  ============================================
        FUNCTION:   chlogHash (STATIC)
        PARAMS:     data - data to be hashed
        RETURNS:    string - hashed value
        ============================================  */
        public static function chlogHash($data) {
           return hash_hmac('sha256', $data, Security::SALT); 
        }
        
        
    /*  ============================================
        FUNCTION:   generateRandomToken (STATIC)
        PARAMS:     (none)
        RETURNS:    string - hashed random value
        ============================================  */
        public static function generateRandomToken() {
           return Security::chlogHash(md5(rand())); 
        }
        
        
    /*  ============================================
        FUNCTION:   generateSessionCookie (STATIC)
        PARAMS:     eml - email to be used
        RETURNS:    string - hashed random value
        ============================================  */
        public static function generateSessionCookie($eml, $fpt, \PDO $dbc=null) {
            
            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();
            
            //generate random values for series and token
            //fingerprint is based on USER_AGENT so identifies browser used
            $series = Self::generateRandomToken();
            $token  = Self::generateRandomToken();
            $fprint = Self::chlogHash($fpt);
            $cookie = $eml . ":" . $series . ":" . $token . ":" . $fprint;
            
            //store session data in database
            try {
                $sql = "CALL addSession(:eml, :ser, :tok, :fpt)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":ser", $series);
                $qry->bindValue(":tok", $token);
                $qry->bindValue(":fpt", $fprint);

                //Check if session record was created ok.
                if (!$qry->execute()) {
                    $errmsg = "Unable to create session data entry.";
                    Logger::log($errmsg); throw new \Exception();
                }
            }
            catch (\PDOException $e) {
                $errmsg = 'Unable to session data entry ('.$e.')';
                Logger::log($errmsg); throw new \Exception($errmsg);
            }
            
            //return the generated cookie, based on session data stored in DB
            return $cookie;
        }
        
        
    /*  ============================================
        FUNCTION:   matchSessionCookie (STATIC)
        PARAMS:     dbc    - database connection object
                    cookie - cookie to be found in DB
                    fprint - the fingerprint string
        RETURNS:    string - if cookie was matched it 
                             will be replaced by a new 
                             cookie, which is returned
        ============================================  */
        public static function matchSessionCookie($cookie, $fprint, \PDO $dbc=null) {

            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();

            //grab the individual variables from the cookie
            list ($eml, $ser, $tok, $fpt) = explode(':', $cookie);

            //if the stored fingerprint doesn't match the current fingerprint then the 
            //cookie has been stolen from another device/browser and should not be used
            if ($fpt != Security::chlogHash($fprint)) {

                $errmsg = 'The remembered user information was not recorded ' .
                                        'in this environment. The information may have been ' .
                                        'compromised and will not be used.';
                
                Logger::log($errmsg); throw new \Exception($errmsg);
            }

            
            //search for session information in the database
            try {
                $sql = "CALL matchSession(:eml, :ser, :tok, :fpt)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":ser", $ser);
                $qry->bindValue(":tok", $tok);
                $qry->bindValue(":fpt", $fpt);
                $qry->execute();
                    
                $matched = $qry->fetch(\PDO::FETCH_ASSOC);    
    
                if ($matched) {
                    //if an exact match could not be found, but the series was found
                    //with a different token, the session has probably been hijacked.
                    if ($matched["token"] == "HIJACK") {
                        $errmsg = 'Your stored session data suggests your ' .
                                            'login details have been hijacked by another ' .
                                            'user. All of your cached sessions will now ' .
                                            'be deleted. You will have to log in again. ' .
                                            'You should consider changing your password. ';
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                } else {
                    return false; 
                }
            }
            catch (\PDOException $e) {
                $errmsg = "Unable to query session data (".$e.")";
                Logger::log($errmsg); throw new \Exception($errmsg);
            }
            
            //if we got here then we matched the cookie data in the database and we are
            //still using the exact same USER_AGENT as recorded the cookie
            
            //now need to replace the token and update the database
            $tok = Self::generateRandomToken();

            try {
                $sql = "CALL replaceSessionToken(:eml, :ser, :tok, :fpt)";
                $qry = $dbc->prepare($sql);
                $qry->bindValue(":eml", $eml);
                $qry->bindValue(":ser", $ser);
                $qry->bindValue(":tok", $tok);
                $qry->bindValue(":fpt", $fpt);
                $replaced = $qry->execute();
                    
                if (!$replaced) {
                   return false; 
                }
            }
            catch (\PDOException $e) {
                $errmsg = 'Unable to update session data ('.$e.')';
                Logger::log($errmsg); throw new \Exception($errmsg);
            }
            
            //if we got here, then the cookie was matched and the token replaced
            //so return the NEW cookie value to be stored by the calling program
            return $eml . ":" . $ser . ":" . $tok . ":" . $fpt;
        }
        
        
    /*  ============================================
        FUNCTION:   removeSessionCookie (STATIC)
        PARAMS:     cookie - cookie to be found in DB
                    fprint - the fingerprint string
                    dbc    - database connection object
        RETURNS:    
        ============================================  */
        public static function removeSessionCookie($cookie, $fprint, \PDO $dbc=null) {

            //if the 'dbc' parameter was not supplied then connect to the 
            //default database using default parameters.
            $dbc = ($dbc) ? : Database::connect();

            if (isset($cookie) && !is_null($cookie)) {
                
                //split cookie to find individual parameters
                list ($eml, $ser, $tok, $fpt) = explode(':', $cookie);
                
                //if the stored fingerprint doesn't match the current fingerprint then the 
                //cookie has been stolen from another device/browser and should not be used
                if ($fpt != Security::chlogHash($fprint)) {
                    $errmsg = 'The remembered user information was not recorded ' .
                                            'in this environment. The information may have been ' .
                                            'compromised and will not be used.';
                    Logger::log($errmsg); throw new \Exception($errmsg);
                }

                //call database procedure to remove session entry
                try {
                    $sql = "CALL removeSession(:eml, :ser, :tok, :fpt)";
                    $qry = $dbc->prepare($sql);
                    $qry->bindValue(":eml", $eml);
                    $qry->bindValue(":ser", $ser);
                    $qry->bindValue(":tok", $tok);
                    $qry->bindValue(":fpt", $fpt);
                    return $qry->execute();
                }
                catch (\PDOException $e) {
                    $errmsg = 'Unable to query session data ('.$e.')';
                    Logger::log($errmsg); throw new \Exception($errmsg);
                }
            }
        }
    }
