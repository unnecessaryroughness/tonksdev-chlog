<?php 

    namespace chlog;

    class Environment {
        
        protected $envid = "XXX";
        protected $logstatus = "ON";

    /*  ============================================
        FUNCTION:   __construct 
        PARAMS:     none
        RETURNS:    (object)
        ============================================  */
        public function __construct() {
            
            if ($_SERVER['HTTP_HOST'] == 'chlog.localhost') {
                $this->envid = "DEV";
            } elseif ($_SERVER['HTTP_HOST'] == 'raspi2') {
                $this->envid = "TST";   
            } else {
                $this->envid = "PRD";
            }
        }        
        
    /*  ============================================
        FUNCTION:   __get
        PARAMS:     field - the read only field required
        RETURNS:    (variable)
        PURPOSE:    General purpose ReadOnly property getter
        ============================================  */
      public function __get( $field ) {
        switch( $field ) {
          case 'envid':
            return $this->envid;
          case 'logstatus':
            return $this->logstatus;
          default:
            throw new \Exception('Invalid property: '.$field);
        }
      }
        
    }
