<?php

    namespace chlog;

    class Attack_View extends ChlogView {
        
        protected $email;
        protected $attack;
        public $triggerlist;
        public $locationlist;
        public $symptomlist;
        public $treatmentlist;
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct($eml, $attack = null) {
            if ($eml) {
                $this->email = $eml;   
            } else {
                throw new \Exception (ChlogErr::EM_FAILEDTOSTARTVIEW, ChlogErr::EC_FAILEDTOSTARTVIEW);   
            }
            
            if ($attack) {
                $this->attack = $attack;
            } else {
                $this->attack = new Attack();
            }
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

            $dtp1 = new datetimepicker_view("dtp1", "Attack Started:");
            $dtp2 = new datetimepicker_view("dtp2", "Attack Was Over:");
            
            return <<<HTML
            <h2>Record an Attack</h2>        

                <form id="frmAttack" action="." method="POST">
                
                
                <section class="attackgroup">
                    <header>Time, Magnitude, Wave</header>
                    
                    <div class="sectionbody">
                        <label for="txtID">Attack ID</label>
                        <input type="text" id="txtID" name="txtID" class="fldNarrow" 
                                readonly value="{$this->attack->id}">

                        {$dtp1->html($this->attack->startdt)}
                        {$dtp2->html($this->attack->enddt)}

                        
                        <label for="rngLevel">Level</label>
                        <div id="kipscale"> 
                            <table><tbody>
                                <tr>
                                    <td class='kipcell'>1</td>
                                    <td class='kipcell'>2</td>
                                    <td class='kipcell'>3</td>
                                    <td class='kipcell'>4</td>
                                    <td class='kipcell'>5</td>
                                </tr>
                                <tr>
                                    <td class='kipcell'>6</td>
                                    <td class='kipcell'>7</td>
                                    <td class='kipcell'>8</td>
                                    <td class='kipcell'>9</td>
                                    <td class='kipcell'>10</td>
                                </tr>
                            </tbody></table>
                        </div>
                        <input type="hidden" id="rngLevel" name="rngLevel" 
                                min="1" max="10" step="1" value="{$this->attack->level}">

                        <label for="txtWave">Wave</label>
                        <input type="text" id="txtWave" name="txtWave" 
                                class="fldNarrow" value="{$this->attack->wave}">
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Triggers</header>
                    <div class="sectionbody">
                        <table id="tblTriggers"><tbody>    
                        {$this->lookuphtml($this->triggerlist)}
                        </tbody></table>
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Pain Locations</header>
                    <div class="sectionbody">
                        <table id="tblLocations"><tbody>    
                        {$this->lookuphtml($this->locationlist)}
                        </tbody></table>
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Symptoms</header>
                    <div class="sectionbody">
                        <table id="tblSymptoms"><tbody>    
                        {$this->lookuphtml($this->symptomlist)}
                        </tbody></table>
                    </div>
                </section>
                
                <section class="attackgroup">
                    <header>Treatments</header>
                    <div class="sectionbody">
                        <table id="tblTreatments"><tbody>    
                        {$this->lookuphtml($this->treatmentlist)}
                        </tbody></table>
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
        FUNCTION:   lookuphtml
        PARAMS:     sl      symptom list to render
        RETURNS:    (string)
        PURPOSE:    returns the default rendering of a symptom list 
        ============================================  */
        function lookuphtml($sl) {
            $rtn = "";
            
            foreach ($sl->records as $sym) {
                $rtn .= "<tr>";
                $rtn .= "<td>".$sym->description."</td>";
                $rtn .= "<td><input type='checkbox' id='chk' name='chk'></td>";
                $rtn .= "</tr>";    
            }
            
            return $rtn;
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
        
