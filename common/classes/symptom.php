<?php

namespace chlog;


class Symptom {

    public $nickname = null;
    public $symptomid = null;
    public $description = null;
    public $sortorder = null;
    public $hidden = false;
    
    public function __construct($nnm = null, $sid = null, $des = null, $srt = null, $hid = null) {
        $this->nickname = $nnm;
        $this->symptomid = $sid;
        $this->description = $des;
        $this->sortorder = $srt;
        $this->hidden = $hid;
    }
    
    
}

