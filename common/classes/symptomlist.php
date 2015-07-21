<?php 

namespace chlog;

class SymptomList implements \Iterator, \ArrayAccess, \Countable {

    public $symptoms = null;
    
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
            $symlist = $jobj->symptoms;
            $cnt = 1;
            
            foreach ($symlist as $s) {
                $this->addSymptom($s->symptomid,
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
            $this->symptoms[] = new Symptom($sid, $des, $srt, $hid, $def, $ods);   
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

    
/*  ============================================
    FUNCTION:   toJSON
    PARAMS:     (none)
    RETURNS:    (string)
    PURPOSE:    Returns the a JSON notation version of this symptoms list
    ============================================  */
    public function toJSON() {
        
        $json = '{"symptoms": [';
        $cnt = 0;
        
        foreach ($this->symptoms as $sym) {
            $json .= str_replace("@@", $cnt, $sym->toJSON()).',';
            $cnt += 1;
        }
        
        $json = substr($json, 0, strlen($json)-1);
        
        $json .= ']}';
        
        return $json;
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
