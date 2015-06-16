<?php

    namespace chlog;
        
    class Login_View {

        public $loggedin = false;
        public $loggedinuser = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct() {}

        
    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            if ($this->loggedin) {
                return "chLOG user";
            } else {
                return "chLOG login";   
            }
        }
        
        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate HTML view for the current state
        ============================================  */
        public function html() {
            if ($this->loggedin) {
                return $this->loggedinhtml();
            } else {
                return $this->loginhtml();
            }
        }
        
    /*  ============================================
        FUNCTION:   loginhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the regular HTML view for the login form
        ============================================  */
        protected function loginhtml() {
            return <<<HTML

            <form id="frmLogin" action="." method="POST">
                <label for="txtEmail">Email:</label>
                <input type="textbox" id="txtEmail" name="email" value="">
                
                <label for="txtPW">Password:</label>
                <input type="password" id="txtPassword" name="password" value="">
                
                <button type="submit" id="btnSubmit" name="action" value="login">Log In</button>
            </form>

            <p><a href="/chlog/register/">Register a New User</a></p>
            <script src="login.js"></script>

HTML;
        }

    /*  ============================================
        FUNCTION:   loggedinhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the  HTML view for when a user is already logged in
        ============================================  */
        protected function loggedinhtml() {
            return <<<HTML
            <p>
            <form id="frmLogout" action="." method="POST">
                User <strong>$this->loggedinuser</strong> Logged In
                <button type="submit" id="btnLogout" name="action" value="logout">Log Out</button>
            </form>
            </p>
HTML;
        }        
        
    }

?>