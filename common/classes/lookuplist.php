<?php

namespace chlog;

class LookupList implements \Iterator, \ArrayAccess, \Countable {
 
    public $records = null;

/*  ============================================
    FUNCTION:   __construct 
    PARAMS:     jso         JSON object representing list to build
    RETURNS:    (object)
    PURPOSE:    constructs the class.
                optionally constructs the class based on a json object spec.
    ============================================  */
    public function __construct($jso = null) {}

        
    
/*  ============================================
    FUNCTION:   getRecord 
    PARAMS:     rid     record id
    RETURNS:    (object)
    PURPOSE:    gets a record object from the list, 
                by record id rather then array position
    ============================================  */
    public function getRecord($rid) {
        foreach ($this->records as $rec) {
            if ($rec->id == $rid) { return $rec; }
        }
        return null;   
    }
    
    
/*  ============================================
    FUNCTION:   sort 
    PARAMS:     (none)
    RETURNS:    (boolean)
    PURPOSE:    Sorts the record list on the sortorder field
    ============================================  */
    public function sort() {
        usort($this->records, function($a, $b) {
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
        foreach ($this->records as $r) {
            if ($r->sortorder < 1000 && $r->sortorder > $max) {
                $max = $r->sortorder;
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
        $cnt = 0;
        $json = '{"record": [';
        foreach ($this->records as $rec) {
            $json .= str_replace("@@", $cnt, $rec->toJSON()).',';
            $cnt += 1;
        }
        $json = substr($json, 0, strlen($json)-1);
        $json .= ']}';
        return $json;
    }
    
    
    /* ITERATOR METHODS */
    
    public function rewind() {
        return reset($this->records);   
    }
    
    public function current() {
        return current($this->records);   
    }
    
    public function key() {
        return key($this->records);   
    }
    
    public function next() {
        return next($this->records);
    }
    
    public function valid() {
        return key($this->records) !== null;   
    }
    
    /* ARRAY ACCESS METHODS */
    
    public function offsetExists($offset) {
        return $this->records[$offset];
    }
    
    public function offsetGet($offset) {
        return $this->records[$offset];
    }
    
    public function offsetSet($offset, $value) {
        $this->records[$offset] = $value;
    }
    
    public function offsetUnset($offset) {
        unset($this->records[$offset]);
    }
     
    /* COUNTABLE METHODS */
    
    public function count() {
        return count($this->records);   
    }
    
}