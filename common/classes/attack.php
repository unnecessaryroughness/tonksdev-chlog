<?php 
/*
  --------------------------------------------------------------------
  attack.php
  --------------------------------------------------------------------
  Defines the basic attack class object.
  --------------------------------------------------------------------
*/

namespace chlog;

    class Attack {

        const SOME_CONST = "";

        protected $id = null;
        protected $email = null;
        protected $startdt = null;
        protected $enddt = null;
        protected $level = null;
        protected $wave = null;

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
        public function __construct($id=null, $em=null, $st=null, $en=null, $lv=null, $wv=null) {
            $this->id = $id;
            $this->email = $em;
            $this->startdt = $st;
            $this->enddt = $en;
            $this->level = $lv;
            $this->wave = $wv;
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
              case 'email':
                return $this->email;
              case 'startdt':
                return $this->startdt;
              case 'enddt':
                return $this->enddt;
              case 'level':
                return $this->level;
              case 'wave':
                return $this->wave;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
    
    }




 
