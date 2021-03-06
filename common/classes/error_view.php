<?php

    namespace chlog;
    
    class Error_View extends ChlogView{

        public $errcode = null;
        public $errmsg = "";
        
        public function __construct($code, $msg) {
            $this->errcode = $code;
            $this->errmsg = $msg;
        }
        
        public function html() {
            return <<<HTML

            <h2>Error</h2>
            <p>Code: $this->errcode</p>
            <br>
            <p>$this->errmsg</p>
HTML;
        }
        
        public function title() {
            return "chLOG Error";   
        }
    }
