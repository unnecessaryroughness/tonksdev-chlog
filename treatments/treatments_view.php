<?php

    namespace chlog;

    class Treatments_View extends ChlogView {
        
        protected $treatmentlist = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct(LookupList $tl = null) {
            if ($tl) {
                $this->treatmentlist = $tl;   
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
            return "chLOG - Administer Treatments";
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

            $tl = $this->treatmentlist;
            $isAdmin = safeget::session("user", "isadmin", false, false);
            
            return <<<HTML
            <h2>Administer Treatments</h2>        
            <div id="lookup-content-area">
                
                <form id="frmLookups" action="." method="POST">
                    
                    <table id="tblLookups"></table>
                    <input type="hidden" id="jsoString" name="jsoString" value="">
                    
                    
                    <div class="divAlignRight">
                        <button type="submit" id="cmdUpdate" name="action" value="update" class="update">Update</button>
                        <button type="button" id="cmdNew" name="new" value="new" class="new">New</button>
                        <button type="submit" id="cmdCancel" name="action" value="cancel" class="cancel">Cancel</button>
                    </div>
                    
                    <div class="endfloat"></div>
                </form>
            </div>
                        
            <div id="modalDialog" class="hidden-modal">
                <h2>Add New Treatment</h2>
                <form>
                    <label for="txtNewRecord">New Treatment Description:</label>
                    <input type="text" id="txtNewRecord" value="">
                </form>
                <button id="cmdAdd" class="update">Add</button>
                <button id="cmdCancel">Cancel</button>
            </div>
            
            <script language="javascript">
                var isAdmin = {$isAdmin};
                var jso = {$this->treatmentlist->toJSON()};
                $("#jsoString").val(JSON.stringify(jso));
            </script>
            
            <script language="javascript" src="/common/templates/modal.js"></script>
            <script language="javascript" src="/common/templates/lookup.js"></script>
HTML;
        }

    /*  ============================================
        FUNCTION:   css
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default CSS path 
        ============================================  */
        public function css() {
            return "/common/styles/chlog-style-lookup.css";   
        }
        
        
    /*  ============================================
        FUNCTION:   json
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default JSON data to use in this view 
        ============================================  */
        public function json() {
            return $this->treatmentlist->toJSON();
        }
    }
        
