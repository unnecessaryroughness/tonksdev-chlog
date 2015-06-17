<?php

    namespace chlog;
    
    class Redirect_View {

        public $redirecturl = null;
        
        public function __construct($url) {
            $this->redirecturl = $url;
        }
        
        public function html() {
            return <<<HTML

            <script language="javascript">
                document.location.href = "$this->redirecturl";
            </script>
HTML;
        }
        
        public function title() {
            return "chLOG Error";   
        }
    }
