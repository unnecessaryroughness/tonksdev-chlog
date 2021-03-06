<?php 

    namespace chlog;

    class ChlogErr {
        
        //USER ERROR CODES
		const EC_USERNOTACTIVE        = 1001;
        const EC_USERBADPWD           = 1002;     
        const EC_USERPWDSNOTMATCHED   = 1003;
        const EC_MISSINGFIELDS        = 1004;
        const EC_REGISTEREMAILFAILED  = 1005;
        const EC_REGISTERUSERFAILED   = 1006;
        const EC_FAILEDACTIVATION     = 1007;
        const EC_USERALREADYACTIVE    = 1008;
        const EC_PROBLEMACTIVATION    = 1009;
        const EC_RECOVERYEMAILFAILED  = 1010;
        const EC_PROBLEMRECOVERY      = 1011;
        const EC_FAILEDRECOVERY       = 1012;
        const EC_MISSINGRECOVERYID    = 1013;
        const EC_REMOVEUSERBADPWD     = 1014;
        
        
        //USER ERROR MESSAGES
        const EM_USERNOTACTIVE        = "This user has not yet been activated. Please check your email inbox and junkmail folder.";
        const EM_USERBADPWD           = "Sorry, this user name / password combination is not valid.";
        const EM_USERPWDSNOTMATCHED   = "Sorry, those new passwords didn't match.";
        const EM_MISSINGFIELDS        = "Sorry, you didn't complete some of the mandatory fields.";
        const EM_REGISTEREMAILFAILED  = "Sorry, the registration email failed to send. Please contact the administrator.";
        const EM_REGISTERUSERFAILED   = "Sorry, the user registration seems to have failed. Please contact the administrator.";
        const EM_FAILEDACTIVATION     = "Sorry, I was unable to activate that user.";
        const EM_USERALREADYACTIVE    = "That user has already been activated";
        const EM_PROBLEMACTIVATION    = "A problem occurred during activation. Please contact the administrator.";
        const EM_RECOVERYEMAILFAILED  = "Sorry, the recovery email failed to send. Please contact the administrator.";
        const EM_PROBLEMRECOVERY      = "A problem occurred during the recover process. Please contact the administrator.";
        const EM_FAILEDRECOVERY       = "Sorry, the user account recovery process seems to have failed. Please contact the administrator.";
        const EM_MISSINGRECOVERYID    = "Sorry, I cannot complete recovery mode without a recovery ID";
        const EM_REMOVEUSERBADPWD     = "Sorry, I cannot remove this user account unless you enter the current password correctly.";
        
        
        //USER ERROR ARRAY
        public static $EA_USERERRORS = array(
                            self::EC_USERNOTACTIVE       => self::EM_USERNOTACTIVE,
                            self::EC_USERBADPWD          => self::EM_USERBADPWD,
                            self::EC_USERPWDSNOTMATCHED  => self::EM_USERPWDSNOTMATCHED,
                            self::EC_MISSINGFIELDS       => self::EM_MISSINGFIELDS,
                            self::EC_REGISTEREMAILFAILED => self::EM_REGISTEREMAILFAILED,
                            self::EC_REGISTERUSERFAILED  => self::EM_REGISTERUSERFAILED,
                            self::EC_FAILEDACTIVATION    => self::EM_FAILEDACTIVATION,
                            self::EC_USERALREADYACTIVE   => self::EM_USERALREADYACTIVE,
                            self::EC_PROBLEMACTIVATION   => self::EM_PROBLEMACTIVATION,
                            self::EC_RECOVERYEMAILFAILED => self::EM_RECOVERYEMAILFAILED,
                            self::EC_PROBLEMRECOVERY     => self::EM_PROBLEMRECOVERY,
                            self::EC_FAILEDRECOVERY      => self::EM_FAILEDRECOVERY,
                            self::EC_MISSINGRECOVERYID   => self::EM_MISSINGRECOVERYID,
                            self::EC_REMOVEUSERBADPWD    => self::EM_REMOVEUSERBADPWD
                    );

        
        //NAVIGATION ERROR CODES
        const EC_INVALIDOPERATION   = 2001;
        const EC_MISMATCHEDUSER     = 2002;
        const EC_FAILEDTOSTARTVIEW  = 2003;
        
        //NAVIGATION ERROR MESSAGES
        const EM_INVALIDOPERATION   = "Sorry, I'm not quite sure what you are asking me to do. Please contact the administrator.";
        const EM_MISMATCHEDUSER     = "Oops, you requested data that isn't meant for you. Naughty.";
        const EM_FAILEDTOSTARTVIEW  = "An error occurred trying to show that page. Please contact the administrator.";
        
        //NAVIGATION ERROR ARRAY
        public static $EA_NAVERRORS = array(
                            self::EC_INVALIDOPERATION  => self::EM_INVALIDOPERATION,
                            self::EC_MISMATCHEDUSER    => self::EM_MISMATCHEDUSER,
                            self::EC_FAILEDTOSTARTVIEW => self::EM_FAILEDTOSTARTVIEW
                    );
        
        
        
        //DATA ACCESS ERROR CODES
        const EC_MISCDATAERR        = 3001;
        
        //DATA ACCESS ERROR MESSAGES
        const EM_MISCDATAERR        = "Oops, I couldn't retrieve the data you asked for. Please contact the administrator.";
        
        //DATA ACCESS ERROR ARRAY
        public static $EA_DATAERRORS = array(
                            self::EC_MISCDATAERR    => self::EM_MISCDATAERR    
            );
        
        
        //LOOKUP ERROR CODES
        const EC_LOOKUPCHANGEFAILED = 4001;
        
        //LOOKUP ERROR MESSAGES
        const EM_LOOKUPCHANGEFAILED = "Uh oh, I was unable to update the lookup data as requested.";
        
        //LOOKUP ERROR ARRAY
        public static $EA_LOOKUPERRORS = array(
                            self::EC_LOOKUPCHANGEFAILED   => self::EM_LOOKUPCHANGEFAILED
            );
        
        
        //ATTACK ERROR CODES
        const EC_ATTACKADDFAILED    = 5001;
        const EC_ATTACKADDNOUSER    = 5002;
        const EC_ATTACKUPDFAILED    = 5003;
        const EC_GETMYATTACKSFAILED = 5004;
        const EC_GETATTACKSNOUSER   = 5005;
        const EC_GETATTACKFAILED    = 5006;
        const EC_GT1OPENATTACK      = 5007;
        const EC_ATTACKDELFAILED    = 5008;
        
        //ATTACK ERROR MESSAGES
        const EM_ATTACKADDFAILED    = "Sorry, I was unable to store those attack details";
        const EM_ATTACKADDNOUSER    = "Sorry, I was unable to figure out your user details, so I can't record your attack. Please log out and back in again.";
        const EM_ATTACKUPDFAILED    = "Sorry, I was unable to amend those attack details";
        const EM_GETMYATTACKSFAILED = "Sorry, I was unable to retrieve a list of your attacks.";
        const EM_GETATTACKSNOUSER   = "Sorry, I was unable to figure out your user details, so I can't retrieve details of your attacks.";
        const EM_GETATTACKFAILED    = "Sorry, I was unable to retrieve that attack.";
        const EM_GT1OPENATTACK      = "Sorry, I could not find an open attack record.";
        const EM_ATTACKDELFAILED    = "Sorry, I was unable to delete that attack record.";
        
        //ATTACK ERROR ARRAY
        public static $EA_ATTACKERRORS = array(
                            self::EC_ATTACKADDFAILED        => self::EM_ATTACKADDFAILED,
                            self::EC_ATTACKADDNOUSER        => self::EM_ATTACKADDNOUSER,
                            self::EC_ATTACKUPDFAILED        => self::EM_ATTACKUPDFAILED,
                            self::EC_GETMYATTACKSFAILED     => self::EM_GETMYATTACKSFAILED,
                            self::EC_GETATTACKSNOUSER       => self::EM_GETATTACKSNOUSER,
                            self::EC_GETATTACKFAILED        => self::EM_GETATTACKFAILED,
                            self::EC_GT1OPENATTACK          => self::EM_GT1OPENATTACK,
                            self::EC_ATTACKDELFAILED        => self::EM_ATTACKDELFAILED
            );


        //TREATMENT PLAN ERROR CODES
        const EC_TREATPLANFAILED    = 6001;
        const EC_REMTPLANFAILED     = 6002;
        const EC_ADDTPLANFAILED     = 6003;
        
        //TREATMENT PLAN ERROR MESSAGES
        const EM_TREATPLANFAILED    = "Sorry, I was unable to retrieve your treatment plan";
        const EM_REMTPLANFAILED     = "Sorry, I was unable to update your treatment plan";
        const EM_ADDTPLANFAILED     = "Sorry, I was unable to update your treatment plan";
        
        
        //TREATMENT PLAN ERROR ARRAY
        public static $EA_TREATMENTPLANERRORS = array(
                            self::EC_TREATPLANFAILED        => self::EM_TREATPLANFAILED,
                            self::EC_REMTPLANFAILED         => self::EM_REMTPLANFAILED,
                            self::EC_ADDTPLANFAILED         => self::EM_ADDTPLANFAILED
            );
        
        
        public static function processRowcount($subject="(none)", $rowcount=0, $errmsg="err", 
                                        $errcode=0, $zeroiserror=false, $gt1isok=false) {
            if ($rowcount == 1) {
                //Logger::log("Updated ".$subject." ok");    
            } elseif ($rowcount > 1) {
                if (!$gt1isok) {
                    Logger::log("More than one ".$subject." record updated. Could be suspicious."); 
                    throw new \Exception($errmsg, $errcode);
                }
            } else { 
                if ($zeroiserror) {
                    Logger::log("Nothing to update for ".$subject." - ".$rowcount." rows updated"); 
                    throw new \Exception($errmsg, $errcode);
                }
            }
        }
        
    }