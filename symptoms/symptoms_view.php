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
            $repeatinggroup = "";
            $thisrow = "";
            
            foreach ($sl as $s) {
                $rowid = '_'.$s->symptomid;
                $thisrow = '<tr>';
                $thisrow .= '<td><label class="fldNarrow fldNumeric">'.$s->symptomid.'</div></td>';
                $thisrow .= '<td><input type="text" class="fldWide fldChar adminonly" id="dsc_'.$rowid.'" name="dsc_'.$rowid.'" value="'.$s->description.'"></td>';
                $thisrow .= '<td><input type="text" class="fldNarrow fldNumeric" id="srt_'.$rowid.'" name="srt_'.$rowid.'" value="'.$s->sortorder.'"></td>';
                $thisrow .= '<td><input type="text" class="fldNarrow fldNumeric" id="hid_'.$rowid.'" name="hid_'.$rowid.'" value="'.$s->hidden.'"></td>';
                $thisrow .= '</tr>';
                
                $repeatinggroup .= $thisrow;
            }
            
            return <<<HTML
            <h2>Administer Symptoms ({$sl->count()})</h2>        
            <div id="symptoms-content-area">
                
                <form id="frmSymptoms" action="." method="POST">
                    
                    <table>
                        <tr>
                            <th class="fldNumeric">id</th>
                            <th class="fldChar">description</th>
                            <th class="fldNumeric">sortorder</th>
                            <th class="fldNumeric">hidden</th>
                        </tr>
                        $repeatinggroup
                    </table>
                    
                    <div class="divAlignRight">
                        <button type="submit" id="cmdUpdate" name="action" value="update" class="update">Update</button>
                        <button type="submit" id="cmdCancel" name="action" value="cancel" class="cancel">Cancel</button>
                    </div>
                    
                    <div class="endfloat"></div>
                </form>
            </div>
HTML;
        }

        public function css() {
            return "/symptoms/symptoms.css";   
        }
        
    }
        
