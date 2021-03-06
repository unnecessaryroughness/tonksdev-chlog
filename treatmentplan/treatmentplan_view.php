<?php

    namespace chlog;

    class Treatmentplan_View extends ChlogView {

        protected $PlanRecs = null;
        protected $Treatments = null;
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct($planrecs, $treatments) {
            $this->PlanRecs = $planrecs;
            $this->Treatments = $treatments;
        }

        
    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG Treatment Plan";   
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
        PURPOSE:    returns the regular HTML view 
        ============================================  */
        protected function defaulthtml() {
            $planjso = buildPlanObjJSO($this->PlanRecs);
            $treatmentOpts = $this->buildTreatmentsOpts();
            
            return <<<HTML

                <script type="text/javascript" src="https://www.google.com/jsapi"></script>

                <form id="frmTplan" action="." method="POST">
                    <h2>My Treatment Plan</h2>
                    
                    
                    <div id="divChart"></div>
                    <div id="divChartCheck">
                        <label for="chkFromToday">Start Chart at Today
                            <input type="checkbox" id="chkFromToday"></input>
                        </label>
                    </div>
                    
                    <div id="rawJSO"></div>
                    
                    <div class="grpTreatmentL">
                        <label for="selTreatment">Treatment</label>
                        <select id="selTreatment" size="6">
                        </select>
                        
                        <button type="button" id="btnAddTre">New</button>
                        <button type="button" id="btnRemTre">Remove</button>
                    </div>

                    <div class="grpTreatmentR">
                        <label>Dosages</label>
                        <table id="tblDosages">
                            <tr class="trHeader">
                                <th class="thsel">Select</th>
                                <th class="thfrom">From</th>
                                <th class="thto">To</th>
                                <th class="thuni">Units</th>
                                <th class="thdos">Dosage</th>
                                <th class="thxday">x/Day</th>
                            </tr>
                        </table>
                    </div>
                    <div id="grpTreatmentBtns" class="endfloat grpTreatmentR">
                        <input type="hidden" id="hidJSO" name="hidJSO" value=""></input>
                        <button type="button" id="btnAddDos">New</button>
                        <button type="submit" id="btnUpdDos" name="action" value="update">Update</button>
                        <button type="button" id="btnRemDos" name="action" value="remove">Remove</button>
                        <button type="button" id="btnRefresh">Refresh Chart</button>
                    </div>
                </form>
                
                <div class="endfloat"></div>
                
                
                <div id="modalDialog" class="hidden-modal">
                    <h2>Add New Treatment</h2>
                    <form>
                        <label for="txtNewTreatment">New Treatment Type:</label>
                        <select id="selNewTre">{$treatmentOpts}</select>
                    </form>
                    <button id="cmdAdd" class="update">Add</button>
                    <button id="cmdCancel">Cancel</button>
                </div>
                
                <div id="divTempCal"></div>
                
                <script>
                    planjso = {$planjso}; 
                </script>
                
                <script src="/common/templates/modal.js"></script>
                <script src="/common/templates/calendar.js"></script>
                <script src="/treatmentplan/treatmentplan.js"></script>
HTML;
        }

        
        public function css() {
            return "/treatmentplan/treatmentplan.css";        
        }
        
        
        private function findTyp($val, $arr) {
            foreach ($arr as $rec) {
                if ($rec["id"] == $val) {
                    return true;
                }
            }
        }
        
        
        private function buildTreatmentsOpts() {
            $rtnVal = "";
            if (sizeof($this->Treatments) > 0) {
                foreach ($this->Treatments as $tre) {
                    $rtnVal .= "<option value='{$tre->id}'>{$tre->description}</option>";
                }
            }
            return $rtnVal;
        }
        
    }
        