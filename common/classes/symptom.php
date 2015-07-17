<?php

namespace chlog;


class Symptom {

    public $nickname = null;
    public $symptomid = null;
    public $description = null;
    public $sortorder = null;
    public $hidden = false;
    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     nnm     nickname
                sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    creates a symptom object
    ============================================  */
    public function __construct($nnm = null, $sid = null, $des = null, $srt = null, $hid = null) {
        $this->nickname = $nnm;
        $this->symptomid = $sid;
        $this->description = $des;
        $this->sortorder = $srt;
        $this->hidden = $hid;
    }
    
    
}

