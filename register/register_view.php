<?php

    namespace chlog;

    class Register_View extends ChlogView {

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
            return "chLOG registration";   
        }
        
        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate HTML view for the current state
        ============================================  */
        public function html() {
            return $this->defaulthtml();    
        }

        
    /*  ============================================
        FUNCTION:   defaulthtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the regular HTML view 
        ============================================  */
        protected function defaulthtml() {
            return <<<HTML
                
                <form id="frmRegister" action="." method="POST">
                    <h2>User Registration</h2>
                    
                    <label for="txtEmail">Email:</label>
                    <input type="textbox" id="txtEmail" name="email" value="">
                    
                    <label for="txtNickname">Nickname:</label>
                    <input type="textbox" id="txtNickname" name="nickname" value="">
                    
                    <label for="txtBiography">Biography:</label>
                    <textarea id="txtBiography" name="biography"></textarea>
                    
                    <label for="txtPassword">Password:</label>
                    <input type="password" id="txtPassword" name="password" value="">
                    
                    <label for="txtPassConf">Confirm Password:</label>
                    <input type="password" id="txtPassConf" name="passconf" value="">
                    
                    <div>
                        <button type="submit" id="txtRegister" name="action" value="register">Register</button>    
                        <button type="submit" id="txtCancel" name="action" value="cancel">Cancel</button>    
                    </div>
                </form>
HTML;
        }
        
    }
