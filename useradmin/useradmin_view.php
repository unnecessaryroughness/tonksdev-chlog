<?php

    namespace chlog;

    class Useradmin_View extends ChlogView {

        protected $user = null;
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct(User $user=null) {
            $this->user = $user;
        }

        
    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG User Admin";   
        }
        
        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate HTML view for the current state
        ============================================  */
        public function html() {
            if ($this->user) {
                return $this->defaulthtml();
            } else {
                return $this->notloggedinhtml();   
            }
        }

        
    /*  ============================================
        FUNCTION:   defaulthtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the regular HTML view 
        ============================================  */
        protected function defaulthtml() {
            if ($this->user) {
                $eml = $this->user->email;
                $nnm = $this->user->nickname;
                $bio = $this->user->biography;
                $jdt = $this->user->joindate;
                $adm = ($this->user->isadmin) ? "YES" : "NO";
                return <<<HTML

                    <form id="frmRegister" action="." method="POST">
                        <p>User Admin Form</p>

                        <label for="txtEmail">Email:</label>
                        <input type="textbox" readonly id="txtEmail" name="email" value="$eml">

                        <label for="txtJoinDate">Join Date:</label>
                        <input type="textbox" readonly id="txtJoinDate" name="joindate" value="$jdt">

                        <label for="txtIsAdmin">Is Admin:</label>
                        <input type="textbox" readonly id="txtIsAdmin" name="isadmin" value="$adm">

                        <label for="txtNickname">Nickname:</label>
                        <input type="textbox" id="txtNickname" name="nickname" value="$nnm">

                        <label for="txtBiography">Biography:</label>
                        <textarea id="txtBiography" name="biography">$bio</textarea>

                        <label for="txtPassword">Current Password:</label>
                        <input type="password" id="txtPassword" name="password" value="">

                        <label for="txtPassConf">New Password:</label>
                        <input type="password" id="txtPassConf" name="passconf" value="">

                        <label for="txtPassConf">Confirm New Password:</label>
                        <input type="password" id="txtPassConf2" name="passconf2" value="">

                        <div>
                            <button type="submit" id="cmdUpdate" name="action" value="update" class="update">Update</button>    
                            <button type="submit" id="cmdCancel" name="action" value="cancel" class="cancel">Cancel</button>    
                        </div>
                        
                        <br><br>
                        <div>
                            <button type="submit" id="cmdRemoveUser" name="action" value="removeuser">Remove User Account</button>
                        </div>

                    </form>
HTML;
            }
        }

    /*  ============================================
        FUNCTION:   notloggedinhtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the HTML view for when there is no logged in user 
        ============================================  */
        protected function notloggedinhtml() {
            return <<<HTML
                <p>No user is currently logged in.</p>       
HTML;
        }
    }
