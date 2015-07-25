<?php

    namespace chlog;
        
    class RecoverPW_View extends ChlogView {

        public $inRecoveryMode = false;
        public $token = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct($rm=false, $tok=null) {
            $this->inRecoveryMode = $rm;
            $this->token = $tok;
        }

        
    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            if ($this->inRecoveryMode) {
                return "chLOG Change Password";   
            } else {
                return "chLOG User Recovery";
            }
        }
        
        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate HTML view for the current state
        ============================================  */
        public function html() {
            if ($this->inRecoveryMode) {
                return $this->changepasswordhtml();
            } else {
                return $this->supplyemailhtml();
            }
        }
        
    /*  ============================================
        FUNCTION:   supplyemailhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the HTML view for supplying an email address
                    when the user's password has been lost
        ============================================  */
        protected function supplyemailhtml() {
            return <<<HTML

            <h2>Account Recovery</h2>
            
            <form id="frmRecover" action="." method="POST">
                <label for="txtEmail">Recover Account for Email:</label>
                <input type="textbox" id="txtEmail" name="email" value="">
                
                <br><br>
                <button type="submit" id="btnSubmit" name="action" value="recover">Recover</button>
                <button type="submit" id="btnCancel" name="action" value="cancel">Cancel</button>
            </form>
HTML;
        }

    /*  ============================================
        FUNCTION:   changepasswordhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the HTML view for when the recovery link
                    has been clicked and the user wishes to supply a 
                    new password
        ============================================  */
        protected function changepasswordhtml() {
            return <<<HTML

            <form id="frmRecover" action="." method="POST">
                <label for="txtPassword">New Password:</label>
                <input type="password" id="txtPassword" name="password" value="">

                <label for="txtPassConf">Confirm Password:</label>
                <input type="password" id="txtPassConf" name="passconf" value="">

                <input type="hidden" id="txtToken" name="token" value="$this->token">
                
                <br><br>
                <button type="submit" id="btnSubmit" name="action" value="complete">Set New Password</button>
                <button type="submit" id="btnCancel" name="action" value="cancel">Cancel</button>
            </form>
HTML;
        }        
        
    }

