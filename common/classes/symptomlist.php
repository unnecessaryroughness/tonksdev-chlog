<?php 

namespace chlog;

class SymptomList extends LookupList {

    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     jso         JSON object representing list to build
    RETURNS:    (object)
    PURPOSE:    constructs the class.
                optionally constructs the class based on a json object spec.
    ============================================  */
    public function __construct($jso = null) {
        if ($jso) {
            $jobj =  json_decode($jso);
            $symlist = $jobj->record;
            $cnt = 1;
            
            foreach ($symlist as $s) {
                $this->addSymptom($s->id,
                                    $s->description,
                                    $cnt,
                                    $s->hidden,
                                    null,
                                    $s->originaldescription);
                $cnt += 1;
            }
        }
    }

    
/*  ============================================
    FUNCTION:   addSymptom 
    PARAMS:     sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    adds a symptom object to the list
    ============================================  */
    public function addSymptom($sid = null, $des = null, $srt = null, $hid = null, $def = null, $ods = null) {
        if ($sid && $des && $srt) {
            $this->records[] = new Symptom($sid, $des, $srt, $hid, $def, $ods);   
        }
    }
    
    
        
}
