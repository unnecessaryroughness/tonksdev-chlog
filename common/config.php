<?php 

    //Include common functions
    require $_SERVER["DOCUMENT_ROOT"]."/common/functions.php";

    //setup autoloaders for TCASH and CHLOG because they share the same website
    //and lingering objects in session might cause issues if both autoloaders are
    //not present all of the time.
    include_once $_SERVER["DOCUMENT_ROOT"] . '/common/chlogautoload.php';
//    include_once $_SERVER["DOCUMENT_ROOT"] . '/tcash/common/tcashautoload.php';

    //set up session & configuration - has to happen in every page
    safesessionstart();

    //Add environment object
    $_SESSION["environment"] = new chlog\Environment();

    //Recover stored user session from cookie
    if (isset($_COOKIE["chlrm"])) {
        
        try {
            //exchange the cookie for a new one in the same series
            $newcookie = chlog\Security::matchSessionCookie(
                $_COOKIE["chlrm"], $_SERVER["HTTP_USER_AGENT"]);   
            
            if ($newcookie) {
                //store the new cookie in the browser. Cache for 14 days from now.
                setcookie('chlrm', $newcookie, time() + 3600 * 24 * 14, '/');
                list ($eml, $ser, $tok, $fpt) = explode(':', $newcookie);

                try {
                    //retrieve user object using newly created cookie details instead of password
                    $_SESSION["user"] = chlog\User::getUserFromSession($eml, $ser, $tok, $fpt);
                    
                } catch (\Exception $e) {
                    unset ($_SESSION["user"]);
                    setcookie("chlrm", "", time()-3600, "/");
                    chlog\Logger::log("Error recovering session", $e->getMessage());   
                }
            }
        } catch (\Exception $e) {
            unset ($_SESSION["user"]);
            setcookie("chlrm", "", time()-3600, "/");
            chlog\Logger::log("Error recovering session", $e->getMessage());   
        }
    }

