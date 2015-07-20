<?php 

namespace chlog;

class SymptomList implements \Iterator, \ArrayAccess, \Countable {

    public $symptoms = null;
    
/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     none
    RETURNS:    (object)
    ============================================  */
    public function __construct() {}

    
/*  ============================================
    FUNCTION:   addSymptom 
    PARAMS:     sid     symptom id
                des     symptom description
                srt     sort order
                hid     hidden
    RETURNS:    (object)
    PURPOSE:    adds a symptom object to the list
    ============================================  */
    public function addSymptom($sid = null, $des = null, $srt = null, $hid = null, $def = null) {
        if ($sid && $des && $srt) {
            $this->symptoms[] = new Symptom($sid, $des, $srt, $hid, $def);   
        } else {
            echo "oops - abend\n";   
        }
    }
    
/*  ============================================
    FUNCTION:   getSymptom 
    PARAMS:     sid     symptom id
    RETURNS:    (object)
    PURPOSE:    gets a symptom object from the list, 
                by symptom id rather then array position
    ============================================  */
    public function getSymptom($sid) {
        
        foreach ($this->symptoms as $sym) {
            if ($sym->symptomid == $sid) { return $sym; }
        }
        
        return null;   
    }

    
/*  ============================================
    FUNCTION:   sort 
    PARAMS:     (none)
    RETURNS:    (boolean)
    PURPOSE:    Sorts the symptom list on the sort order field
    ============================================  */
    public function sort() {
        usort($this->symptoms, function($a, $b) {
            return $a->sortorder - $b->sortorder;   
        });
    }
    
    
/*  ============================================
    FUNCTION:   getMaxSortOrder 
    PARAMS:     (none)
    RETURNS:    (integer)
    PURPOSE:    Returns the highest sort order (that is < 1000)
    ============================================  */
    public function getMaxSortOrder() {
        $max = 0;
        foreach ($this->symptoms as $s) {
            if ($s->sortorder < 1000 && $s->sortorder > $max) {
                $max = $s->sortorder;
            }
        }
        return $max;
    }

    
    /* ITERATOR METHODS */
    
    public function rewind() {
        return reset($this->symptoms);   
    }
    
    public function current() {
        return current($this->symptoms);   
    }
    
    public function key() {
        return key($this->symptoms);   
    }
    
    public function next() {
        return next($this->symptoms);
    }
    
    public function valid() {
        return key($this->symptoms) !== null;   
    }
    
    /* ARRAY ACCESS METHODS */
    
    public function offsetExists($offset) {
        return $this->symptoms[$offset];
    }
    
    public function offsetGet($offset) {
        return $this->symptoms[$offset];
    }
    
    public function offsetSet($offset, $value) {
        $this->symptoms[$offset] = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->symptoms[$offset]);
    }
     
    /* COUNTABLE METHODS */
    
    public function count() {
        return count($this->symptoms);   
    }
}
