<?php

    namespace chlog;
    
    class Activate_View {

        public function __construct() {}
        
        public function html() {
            return <<<HTML

            <h2>User Activated</h2>
            <hr>
            <p>Now please <a href="/chlog/login/">login</a></p>
HTML;
        }
        
        public function title() {
            return "chLOG User Activation";   
        }
    }

?>
