<?php
    /*
      --------------------------------------------------------------------
      database.php
      --------------------------------------------------------------------
      Defines the database class to connect to the database server
      and assorted database specific administration functions.
      --------------------------------------------------------------------
    */

    namespace chlog;

    class Database {

        //DEV constants
		const DEV_HOSTNAME = 'localhost';
		const DEV_DBNAME = 'tonksdev_chlog';
		const DEV_UNAME = 'tonksdev_chlogu';
		const DEV_PWD = 'LA:553425';

        //TST constants
        const RASPI2_HOSTNAME = 'raspi2';
		const RASPI2_DBNAME = 'tonksdev_chlog';
		const RASPI2_UNAME = 'tonksdev_chlogu';
		const RASPI2_PWD = 'LA:553425';

        //PRD constants
        const WWW_HOSTNAME = '10.169.0.20';
		const WWW_DBNAME = 'tonksdev_chlog';
		const WWW_UNAME = 'tonksdev_chlogu';
		const WWW_PWD = 'LA:553425';
        
    /*  ============================================
        FUNCTION:   connect 
        PARAMS:     envname - the current environment
        RETURNS:    PDO object
        ============================================  */
        public static function connect($envname=null) {
                        
            //if no environment name passed, default to whatever ID 
            //is stored in the session environment settings object
            $envname = $envname ? : safeget::session("environment", "envid", "DEV", false);
            
            //Set the database parameters, based on the environment
            //variable passed in, or retrieved from session.
            if ($envname == "PRD") {
                //Production environment
                $hostname  = Database::WWW_HOSTNAME;
                $dbname    = Database::WWW_DBNAME;
                $uname     = Database::WWW_UNAME;
                $pwd       = Database::WWW_PWD;
                
            } elseif ($envname == "TST") {
                //TST test environment
                $hostname  = Database::RASPI2_HOSTNAME;
                $dbname    = Database::RASPI2_DBNAME;
                $uname     = Database::RASPI2_UNAME;
                $pwd       = Database::RASPI2_PWD;
                
            } else {
                //default to DEV environment
                $hostname  = Database::DEV_HOSTNAME;
                $dbname    = Database::DEV_DBNAME;
                $uname     = Database::DEV_UNAME;
                $pwd       = Database::DEV_PWD;
            }
            

            //Connect to the database using PDO. Configure default settings.
            //Return the PDO object as the return value.
            try {
                $pdo = new \PDO('mysql:host=' . $hostname . ';dbname=' . $dbname, $uname, $pwd);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec('SET NAMES "utf8"');
                //logger::log("Connected to database [".$envname."]");
                return $pdo;
                
            } catch (\PDOException $e) {
                logger::log("Unable to connect to database [".$envname."]");
                throw new \Exception ('Unable to connect to the database server (' 
                                     . $hostname . '/' . $dbname . ')');
            }
        }
        
    }
