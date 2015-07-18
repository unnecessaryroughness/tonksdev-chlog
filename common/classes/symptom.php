<?php

namespace chlog;


class Symptom {

    public $symptomid = null;
    public $description = null;
    public $sortorder = null;
    public $hidden = false;
    public $originaldescription = null;
    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    creates a symptom object
    ============================================  */
    public function __construct($sid = null, $des = null, $srt = null, $hid = null) {
        $this->symptomid = $sid;
        $this->description = $des;
        $this->originaldescription = $des;
        $this->sortorder = $srt;
        $this->hidden = $hid;
    }

    
    /*  ============================================
        FUNCTION:   __get
        PARAMS:     field - the read only field required
        RETURNS:    (variable)
        PURPOSE:    General purpose ReadOnly property getter
        ============================================  */
        public function __get( $field ) {
            switch( $field ) {
              case 'symptomid':
                return $this->symptomid;
              case 'description':
                return $this->description;
              case 'originaldescription':
                return $this->originaldescription;
              case 'descriptionhaschanged':
                return $this->description == $this->originaldescription ? false : true;
              case 'sortorder':
                return $this->sortorder;
              case 'hidden':
                return $this->hidden;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
    
/*  ============================================
    FUNCTION:   updateAdmin 
    PARAMS:     des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    Updates a symptom object. 
                This is the administrator version
                that also allows updating of the
                description.
    ============================================  */
    public function updateAdmin($des = null, $srt = null, $hid = null) {
        if ($des) { $this->description = $des; }
        if ($srt) { $this->sortorder = $srt; }
        if ($hid) { $this->hidden = $hid; }
    }
    
    
/*  ============================================
    FUNCTION:   update 
    PARAMS:     srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    Updates a symptom object. 
    ============================================  */
    public function update($srt = null, $hid = null) {
        $this->sortorder = $srt;
        $this->hidden = $hid;
    }
    
}

