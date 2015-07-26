<?php

    namespace chlog;

    class Triggers_View extends ChlogView {
        
        protected $triggerlist = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct(LookupList $tl = null) {
            if ($tl) {
                $this->triggerlist = $tl;   
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
            return "chLOG - Administer Triggers";
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

            $tl = $this->triggerlist;
            $isAdmin = safeget::session("user", "isadmin", false, false);
            
            return <<<HTML
            <h2>Administer Triggers</h2>        
            <div id="triggers-content-area">
                
                <form id="frmTriggers" action="." method="POST">
                    
                    <table id="tblTriggers"></table>
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
                <h2>Add New Symptom</h2>
                <form>
                    <label for="txtNewTrigger">New Trigger Description:</label>
                    <input type="text" id="txtNewTrigger" value="">
                </form>
                <button id="cmdAdd" class="update">Add</button>
                <button id="cmdCancel">Cancel</button>
            </div>
            
            <script language="javascript">
                var isAdmin = {$isAdmin};
                var jso = {$this->triggerlist->toJSON()};
                $("#jsoString").val(JSON.stringify(jso));
            </script>
            
            <script language="javascript" src="triggers.js"></script>
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
            return "/triggers/triggers.css";   
        }
        
        
    /*  ============================================
        FUNCTION:   json
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default JSON data to use in this view 
        ============================================  */
        public function json() {
            return $this->triggerlist->toJSON();
        }
    }
        
