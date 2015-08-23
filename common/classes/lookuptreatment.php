<?php

namespace chlog;

class LookupTreatment extends Lookup {
    
    protected   $preparation = null;
    protected   $dosage = null;
    protected   $administered = null;
    
    public function setTreatmentParams($pre, $dos, $adm) {
        $this->preparation = $pre ? $pre : null;
        $this->dosage = $dos ? $dos : null;
        $this->administered = $adm ? $adm : null;
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
            return $this->id;
          case 'preparation':
            return $this->preparation;
          case 'dosage':
            return $this->dosage;
          case 'administered':
            return $this->administered;
          default:
            throw new \Exception('Invalid property: '.$field);
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
                ', "sortorder": '.($this->sortorder ? $this->sortorder : "0").
                ', "hidden": '.($this->hidden ? $this->hidden : "0").
                ', "preparation": "'.$this->preparation.'"'.
                ', "dosage": "'.$this->dosage.'"'.
                ', "administered": "'.$this->administered.'"'.
                ', "sequence": 0 }';
    }    
    
}


