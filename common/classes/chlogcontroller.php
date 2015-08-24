<?php

    namespace chlog;
    
    class ChlogController {
        public function process($type, $fields) {
            return new Redirect_View("/");
        }
        
        protected function notLoggedIn() {
            $usr = safeget::session("user", null, null, false);
            if (!$usr) {
                return new Redirect_View("/login/");   
            } else {
                return false;   
            }
        }
    }

