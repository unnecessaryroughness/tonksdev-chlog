<?php 

    namespace chlog;

    class Login_Control {

        protected $loginview = null;
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     vw - login_view object
        RETURNS:    (object)
        PURPOSE:    constructs the class & assigns an associated view, if one was passed in
        ============================================  */
        public function __construct(Login_View $vw) {
            if (isset($vw)) {
                $this->loginview = $vw;   
            }
        }
        
    /*  ============================================
        FUNCTION:   LoginView
        PARAMS:     (none)
        RETURNS:    (object) login_view object
        PURPOSE:    returns the current login view object for this controller, 
                    or assigns one if the view is currently null.
        ============================================  */
        protected function LoginView() {
            $this->loginview = ($this->loginview) ? : new Login_View();   
            return $this->loginview;
        }
        
    /*  ============================================
        FUNCTION:   process
        PARAMS:     type    - the type of process that needs to be run
                    fields  - a key/value pair of field data to support the process
        RETURNS:    (string) HTML data
        PURPOSE:    returns the relevant HTML markup for display, from the login view object
        ============================================  */
        public function process($type, $fields) {
            
            switch ($type) {
                
                case "login":
                    //get login details from POST
                    $eml = safeget::kvp($fields, "email", "null@null.null", false);
                    $pwd = safeget::kvp($fields, "password", "null", false);
                              
                    //attempt to create the user from the supplied details
                    //and add to session as the current user, then return page html
                    try {
                        $_SESSION["user"] = User::getUserFromEmail($eml, $pwd);
                        $errmsg = "User ".$eml." logged in"; Logger::log($errmsg);
                        
                        $nnm = safeget::session("user", "nickname", "unknown"); 
                        return $this->LoginView()->loggedinhtml($nnm);                
                        
                    } catch (\Exception $e) {
                        $errmsg = "error retrieving user ".$eml." from login form ";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                    break;
                
                case "logout":
                    unset($_SESSION["user"]);
                    return $this->LoginView()->html();
                    break;
                
                case "unset":
                    //If the response action is unset then do nothing & show the form
                    $unn = safeget::session("user", "nickname", "");
                    $rtn = (strlen($unn)==0) ? $this->LoginView()->html() : $this->LoginView()->loggedinhtml($unn);
                    return $rtn;
                    break;
                
                default:
                    //uh-oh - what is this? Throw an error!
                    throw new \Exception ("Unhandled response from page.".$type );
                    break;
            }
        }

        
    }


?>
