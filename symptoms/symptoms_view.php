<?php

    namespace chlog;

    class Symptoms_View extends ChlogView {
        
        protected $symptomlist = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct(SymptomList $sl = null) {
            if ($sl) {
                $this->symptomlist = $sl;   
            } else {
                throw new \Exception (ChlogErr::EM_FAILEDTOSTARTVIEW, ChlogErr::EC_FAILEDTOSTARTVIEW);   
            }
        }


    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG - Administer Symptoms";
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

            $sl = $this->symptomlist;
            $isAdmin = safeget::session("user", "isadmin", false, false);
            
            return <<<HTML
            <h2>Administer Symptoms</h2>        
            <div id="symptoms-content-area">
                
                <form id="frmSymptoms" action="." method="POST">
                    
                    <table id="tblSymptoms"></table>
                    <input type="hidden" id="jsoSymptoms" name="jsosymptoms" value="">
                    
                    
                    <div class="divAlignRight">
                        <button type="submit" id="cmdUpdate" name="action" value="update" class="update">Update</button>
                        <button type="submit" id="cmdCancel" name="action" value="cancel" class="cancel">Cancel</button>
                    </div>
                    
                    <div class="endfloat"></div>
                </form>
            </div>
            
            <script language="javascript">
                var isAdmin = {$isAdmin};
                var jso = {$this->symptomlist->toJSON()};
                $("#jsoSymptoms").val(JSON.stringify(jso));
            </script>
            
            <script language="javascript" src="symptoms.js"></script>
HTML;
        }

    /*  ============================================
        FUNCTION:   css
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default CSS path 
        ============================================  */
        public function css() {
            return "/symptoms/symptoms.css";   
        }
        
        
    /*  ============================================
        FUNCTION:   json
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default JSON data to use in this view 
        ============================================  */
        public function json() {
            return $this->symptomlist->toJSON();
        }
    }
        
