<?php 

namespace chlog;

class SymptomList {

    public $symptoms = null;
    
    public function __construct() {}
    
    public function addSymptom($nnm = null, $sid = null, $des = null, $srt = null, $hid = null) {
        if ($nnm && $sid && $des && $srt) {
            $this->symptoms[] = new Symptom($nnm, $sid, $des, $srt, $hid);   
        } else {
            echo "oops - abend\n";   
        }
    }
    
    public function getSymptom($sid) {
        
        foreach ($this->symptoms as $sym) {
            if ($sym->symptomid == $sid) { return $sym; }
        }
        
        return null;   
    }
    
}
