<?php

    namespace chlog;

    class Attack_View extends ChlogView {
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct() {
            //throw new \Exception (ChlogErr::EM_FAILEDTOSTARTVIEW, ChlogErr::EC_FAILEDTOSTARTVIEW);   
        }


    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG - Add/Amend Attack";
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

            $cal1 = new calendar_view("c1");
            $cal2 = new calendar_view("c2");
            
            return <<<HTML
            <h2>Add/Amend Attack Details</h2>        

                <form id="frmAttack" action="." method="POST">
                
                
                <section class="attackgroup">
                    <header>Time, Magnitude, Wave</header>
                    
                    <div class="sectionbody">
                        <label for="txtID">Attack ID</label>
                        <input type="text" id="txtID" name="txtID" class="fldNarrow" readonly value="">

                        <label for="txtStart">Attack Started:</label>
                        <input type="text" id="txtStartDate" name="txtStartDate" class="fldWider" value="">
                        <input type="text" id="txtStartTime" name="txtStartTime" class="fldNarrow" value="">
                        <div class="caldiv">{$cal1->html()}</div>

                        <label for="txtEnd">Attack Was Over:</label>
                        <input type="text" id="txtEndDate" name="txtEnd" class="fldWider" value="">
                        <input type="text" id="txtEndTime" name="txtEndTime" class="fldNarrow" value="">
                        <div class="caldiv">{$cal2->html()}</div>

                        <label for="rngLevel">Level</label>
                        <input type="range" id="rngLevel" name="rngLevel" min="1" max="10" step="1" value="1">

                        <label for="txtWave">Wave</label>
                        <input type="text" id="txtWave" name="txtWave" class="fldNarrow" value="">
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Triggers</header>
                    <div class="sectionbody">
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Pain Locations</header>
                    <div class="sectionbody">
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Symptoms</header>
                    <div class="sectionbody">
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Treatments</header>
                    <div class="sectionbody">
                    </div>
                </section>
                
                
                <div class="endfloat divAlignRight">
                    <button type="submit" id="cmdUpdate" name="action" value="update" class="update">Update</button>
                    <button type="submit" id="cmdCancel" name="action" value="cancel" class="cancel">Cancel</button>
                </div>
                
                <div class="endfloat"></div>
            </form>

            <script src="/common/templates/calendar.js"></script>
            <script src="attack.js"></script>
HTML;
        }

    /*  ============================================
        FUNCTION:   css
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default CSS path 
        ============================================  */
        public function css() {
            return "/attack/attack.css";   
        }
        
        
    /*  ============================================
        FUNCTION:   json
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default JSON data to use in this view 
        ============================================  */
        public function json() {}
    }
        
