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
        
        protected $symptoms = null;
        protected $triggers = null;
        protected $locations = null;
        protected $treatments = null;

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
                return substr($this->startdt, 0, -3);
              case 'enddt':
                return substr($this->enddt, 0, -3);
              case 'level':
                return $this->level;
              case 'wave':
                return $this->wave;
              case 'symptoms':
                return $this->symptoms;
              case 'triggers':
                return $this->triggers;
              case 'locations':
                return $this->locations;
              case 'treatments':
                return $this->treatments;
              default:
                throw new \Exception('Invalid property: '.$field);
            }
        }
    
    /*  ============================================
        FUNCTION:   attachSymptoms
        PARAMS:     array - an array of symptom objects
        RETURNS:    (none)
        PURPOSE:    attaches an array of symptoms to this attack
        ============================================  */
        public function attachSymptoms($aSym) {
            if ($aSym) {
                $this->symptoms = $aSym;
            }
        }
        
    /*  ============================================
        FUNCTION:   attachLocations
        PARAMS:     array - an array of location objects
        RETURNS:    (none)
        PURPOSE:    attaches an array of locations to this attack
        ============================================  */
        public function attachlocations($aLoc) {
            if ($aLoc) {
                $this->locations = $aLoc;
            }
        }
        
    /*  ============================================
        FUNCTION:   attachtreatments
        PARAMS:     array - an array of treatment objects
        RETURNS:    (none)
        PURPOSE:    attaches an array of treatments to this attack
        ============================================  */
        public function attachtreatments($aTre) {
            if ($aTre) {
                $this->treatments = $aTre;
            }
        }
        
    /*  ============================================
        FUNCTION:   attachTriggers
        PARAMS:     array - an array of trigger objects
        RETURNS:    (none)
        PURPOSE:    attaches an array of triggers to this attack
        ============================================  */
        public function attachTriggers($aTrg) {
            if ($aTrg) {
                $this->triggers = $aTrg;
            }
        }
        
        
    }




 
