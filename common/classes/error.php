<?php

    namespace chlog;
    
    class Error {

        public function __construct() {}
        
        public function html($errmsg) {
            return <<<HTML

            <h2>Error</h2>
            <hr>
            <p>$errmsg</p>
            <hr>
HTML;
        }
    }

?>
