<?php

    namespace chlog;

    class Review_View extends ChlogView {
        
        protected $attacks = null;
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct($recs) {
            $this->records = $recs;
        }


    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG - Review My Attacks";
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

            $ins = "";
            
            foreach ($this->records as $rec) {
                $slnk = "<a href='/attack/?id=".$rec->id ."'>";
                $elnk = "</a>";
                
                $ins .= "<article id='a{$rec->id}'>{$slnk}";
                $ins .=     "<span class='spnRef'>{$rec->id}</span>";
                $ins .=     "<span class='spnLev'>{$rec->level}</span>";
                $ins .=     "<span class='spnFrom'>{$rec->startdt}</span>";
                $ins .=     "<span class='spnTo'>{$rec->enddt}</span>";
                $ins .= "{$elnk}</article>";   
            }
            
            return <<<HTML
            <h2>Review My Attacks</h2>        
            
            <div class="divAttackListHeader">
                <span class="spnRef">Ref#</span><span class="spnLev">Level</span><span class="spnFrom">From</span><span class="spnTo">To</span>
            </div>
            
            <div class="divAttackList">
                {$ins}
            </div>


            <script src="review.js"></script>
HTML;
        }

    /*  ============================================
        FUNCTION:   css
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default CSS path 
        ============================================  */
        public function css() {
            return "/review/review.css";   
        }
        
        
    /*  ============================================
        FUNCTION:   json
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default JSON data to use in this view 
        ============================================  */
        public function json() {}
    }
        
