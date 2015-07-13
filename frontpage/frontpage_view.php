<?php

    namespace chlog;
        
    class Frontpage_View extends ChlogView {

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
            return "chLOG - Online cluster headache tracking and reporting";
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
        PURPOSE:    returns the default HTML view 
        ============================================  */
        public function defaulthtml() {
            return <<<HTML
            <div class="chlog-main-splash">chLOG</div>        
            <div id="chlog-content-area"></div>
HTML;
        }

        public function css() {
            return "/frontpage/chlog-frontpage.css";   
        }
        
    }


    