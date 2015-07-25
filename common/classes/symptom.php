<?php

namespace chlog;


class Symptom {

    protected $id = null;
    protected $description = null;
    protected $sortorder = null;
    protected $hidden = false;
    protected $defaultsort = null;
    protected $originaldescription = null;
    protected $isdirty = false;
    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
                def     default sort order
                ods     original description
    RETURNS:    (object)
    PURPOSE:    creates a symptom object
    ============================================  */
    public function __construct($sid = null, $des = null, $srt = null, $hid = null, $def = null, $ods = null) {
        $this->id = $sid;
        $this->description = $des;
        $this->sortorder = $srt;
        $this->hidden = $hid;
        $this->defaultsort = $def;
        $this->originaldescription = $ods ? $ods : $des;
        $this->isdirty = 1;
    }

    
    /*  ============================================
        FUNCTION:   __get
        PARAMS:     field - the read only field required
        RETURNS:    (variable)
        PURPOSE:    General purpose ReadOnly property getter
        ============================================  */
        public function __get( $field ) {
            switch( $field ) {
              case 'id':
                return $this->id;
              case 'description':
                return $this->description;
              case 'originaldescription':
                return $this->originaldescription;
              case 'descriptionhaschanged':
                return $this->description == $this->originaldescription ? false : true;
              case 'sortorder':
                return $this->sortorder;
              case 'defaultsort':
                return $this->defaultsort;
              case 'hidden':
                return $this->hidden;
              case 'isdirty':
                return $this->isdirty;
              case 'isnew':
                return $this->id ? false : true;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
    
    
/*  ============================================
    FUNCTION:   setDirty 
    PARAMS:     bln     boolean value representing dirty/clean status
    RETURNS:    (object)
    PURPOSE:    Updates a symptom object "isdirty" flag 
    ============================================  */
    public function setDirty($bln) {
        $this->isdirty = $bln;   
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
        if ($des) { 
            $this->description = $des; 
            $this->dirty = true;
        }
        
        $this->update($srt, $hid);
    }
    
    
/*  ============================================
    FUNCTION:   update 
    PARAMS:     srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    Updates a symptom object. 
    ============================================  */
    public function update($srt = null, $hid = null) {
        //Only process if the sort order is populated and has changed AND if the symptom is not hidden
        if (isset($srt) && $srt != $this->sortorder && (!isset($hid) || !$hid)) { 
            $this->sortorder = $srt > 0 ? $srt : $this->defaultsort; 
            $this->isdirty = true;
        }
        
        if (isset($hid) && $hid != $this->hidden) { 
            $this->hidden = $hid; 
            $this->isdirty = true;
        }
    }
    
    
    
/*  ============================================
    FUNCTION:   toJSON
    PARAMS:     (none)
    RETURNS:    (string)
    PURPOSE:    Returns the a JSON notation version of this symptom
    ============================================  */
    public function toJSON() {
        return '{"id": '.$this->id.
                ', "description": "'.$this->description.'"'.
                ', "originaldescription": "'.$this->originaldescription.'"'.
                ', "sortorder": '.$this->sortorder.
                ', "hidden": '.$this->hidden.
                ', "sequence": @@ }';
    }

}

