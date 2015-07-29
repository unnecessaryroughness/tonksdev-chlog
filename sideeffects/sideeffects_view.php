<?php

    namespace chlog;

    class SideEffects_View extends ChlogView {
        
        protected $sideeffectlist = null;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     $sl     side effects list object
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct(LookupList $sl = null) {
            if ($sl) {
                $this->sideeffectlist = $sl;   
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
            return "chLOG - Administer Side Effects";
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

            $tl = $this->sideeffectlist;
            $isAdmin = safeget::session("user", "isadmin", false, false);
            
            return <<<HTML
            <h2>Administer Side Effects</h2>        
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
                <h2>Add New Side Effect</h2>
                <form>
                    <label for="txtNewRecord">New Side Effect Description:</label>
                    <input type="text" id="txtNewRecord" value="">
                </form>
                <button id="cmdAdd" class="update">Add</button>
                <button id="cmdCancel">Cancel</button>
            </div>
            
            <script language="javascript">
                var isAdmin = {$isAdmin};
                var jso = {$this->sideeffectlist->toJSON()};
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
            return $this->sideeffectlist->toJSON();
        }
    }
        
