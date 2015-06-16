<?php

    namespace chlog;
        
    class Login_View {

        const pgtitle = "chLOG Login";

    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct() {}

        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the regular HTML view for the login form
        ============================================  */
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

    /*  ============================================
        FUNCTION:   loggedinhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the  HTML view for when a user is already logged in
        ============================================  */
        public function loggedinhtml($nickname) {
            return <<<HTML
            <p>
            <form id="frmLogout" action="." method="POST">
                User <strong>$nickname</strong> Logged In
                <button type="submit" id="btnLogout" name="action" value="logout">Log Out</button>
            </form>
            </p>
HTML;
        }        
        
    }

?>