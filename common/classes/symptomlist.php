<?php 

namespace chlog;

class SymptomList {

    public $symptoms = null;
    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     none
    RETURNS:    (object)
    ============================================  */
    public function __construct() {}

    
/*  ============================================
    FUNCTION:   addSymptom 
    PARAMS:     nnm     nickname
                sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    adds a symptom object to the list
    ============================================  */
    public function addSymptom($nnm = null, $sid = null, $des = null, $srt = null, $hid = null) {
        if ($nnm && $sid && $des && $srt) {
            $this->symptoms[] = new Symptom($nnm, $sid, $des, $srt, $hid);   
        } else {
            echo "oops - abend\n";   
        }
    }
    
/*  ============================================
    FUNCTION:   getSymptom 
    PARAMS:     sid     symptom id
    RETURNS:    (object)
    PURPOSE:    gets a symptom object from the list, by symptom id
    ============================================  */
    public function getSymptom($sid) {
        
        foreach ($this->symptoms as $sym) {
            if ($sym->symptomid == $sid) { return $sym; }
        }
        
        return null;   
    }
    
}
