<?php 
/*
  --------------------------------------------------------------------
  attack.php
  --------------------------------------------------------------------
  Defines the basic attack class object.
  --------------------------------------------------------------------
*/

namespace chlog;

    class Symptom {

        const SOME_CONST = "";

        protected $id = null;
        protected $description = null;

    /*  ============================================
        FUNCTION:   __construct()
        PARAMS:     id      id of attack record
                    em      email of user who recorded the attack
                    st      start date/time of attack
                    en      end date/time of attack
                    lv      level of attack on the KIP scale
                    wv      wave of an attack
        RETURNS:    (object)
        ============================================  */
        public function __construct($id=null, $de=null) {
            $this->id = $id;
            $this->description = $de;
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
                return $this->decription;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
    
    }




 
