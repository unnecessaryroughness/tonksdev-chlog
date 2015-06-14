<?php

    namespace chlog;

    class Login {

        const pgtitle = "chLOG Login";
        
        public function __construct() {}

        public function html() {
            return <<<HTML

            <form id="frmLogin" action="." method="POST">
                <label for="txtEmail">Email:
                    <input type="textbox" id="txtEmail" name="email" value="">
                </label>
                <label for="txtPW">Password:
                    <input type="password" id="txtPassword" name="password" value="">
                </label>
                <button type="submit" id="btnSubmit" name="action" value="login">Log In</button>
            </form>

            <script src="login.js"></script>

HTML;
        }

        public function loggedinhtml() {
            //TODO   
        }
        
        public function handleResponse($type, $fields) {
            
            switch ($type) {
                
                case "login":
                    //get login details from POST
                    $eml = safeget::post("email", "null@null.null", false);
                    $pwd = safeget::post("password", "null", false);
                    
                    //attempt to create the user from the supplied details
                    //and add to session as the current user
                    //then return page html
                    //TODO: replace this with a "currently logged in html"
                    try {
                        $_SESSION["user"] = User::getUserFromEmail($eml, $pwd);
                        return $this->html();
                        
                    } catch (\Exception $e) {
                        $errmsg = "error retrieving user ".$eml." from login form ";
                        Logger::log($errmsg); throw new \Exception($errmsg);
                    }
                    break;
                
                case "unset":
                    //If the response action is unset then do nothing & show the form
                    return $this->html();
                    break;
                
                default:
                    //uh-oh - what is this? Throw an error!
                    throw new \Exception ("Unhandled response from page.".$type );
                    break;
            }
        }
        
        
    }

?>