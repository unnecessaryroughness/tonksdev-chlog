<?php

    /*
      --------------------------------------------------------------------
      safeget.php
      --------------------------------------------------------------------
      Defines static functions for safely checking values from session, 
      server, etc. Does not error if the session, server, etc.  is not 
      initialised, or if the object or value is not recorded in session.
      All failure cases return the supplied default value or an empty string.
      --------------------------------------------------------------------
    */

    namespace chlog;
    
    class safeget {
        
    /*  ============================================
        FUNCTION:   session (STATIC)
        PARAMS:     object - the object name to interrogate
                    property - the property of the object to return
                    default - the default value to return in case of error
                    returnblanks - (boolean) should we return a blank or the default value 
                              if the found property value is blank
        RETURNS:    value
        ============================================  */
        public static function session($object, $property, $default="", $returnblanks=true) {
            
            if (!isset($_SESSION)) {
                return $default;   
            } else {
                if (!isset($_SESSION[$object])) {
                    return $default;   
                } else {
                    if (!$property) {
                        return $_SESSION[$object];
                    } else {
                        if (strlen($_SESSION[$object]->$property)==0 && !returnblanks) {
                            return $default;
                        } else {
                            return $_SESSION[$object]->$property;   
                        }
                    }
                }
            }
        }
                        
    /*  ============================================
        FUNCTION:   session (STATIC)
        PARAMS:     property - the property of the object to return
                    default - the default value to return in case of error
                    returnblanks - (boolean) should we return a blank or the default value 
                              if the found property value is blank
        RETURNS:    value
        ============================================  */
        public static function server($property, $default="", $returnblanks=true) {
            
            if (!isset($_SERVER)) {
                return $default;   
            } else {
                if (!isset($_SERVER[$property])) {
                    return $default;   
                } else {
                    if (strlen($_SERVER[$property])==0 && !$returnblanks) {
                        return $default;
                    } else {
                        return $_SERVER[$property];
                    }
                }
            }
        }
    
        
    /*  ============================================
        FUNCTION:   post (STATIC)
        PARAMS:     property - the property of the array to return
                    default - the default value to return in case of error
                    returnblanks - (boolean) should we return a blank or the default value 
                              if the found property value is blank
        RETURNS:    value
        ============================================  */
        public static function post($property, $default="", $returnblanks=true) {

            if (!isset($_POST)) {
                return $default;   
            } else {
                if (!isset($_POST[$property])) {
                    return $default;   
                } else {
                    if (strlen($_POST[$property])==0 && !$returnblanks) {
                        return $default;
                    } else {
                        return $_POST[$property];
                    }
                }
            }
        }

        
    /*  ============================================
        FUNCTION:   get (STATIC)
        PARAMS:     property - the property of the array to return
                    default - the default value to return in case of error
                    returnblanks - (boolean) should we return a blank or the default value 
                              if the found property value is blank
        RETURNS:    value
        ============================================  */
        public static function get($property, $default="", $returnblanks=true) {

            if (!isset($_GET)) {
                return $default;   
            } else {
                if (!isset($_GET[$property])) {
                    return $default;   
                } else {
                    if (strlen($_GET[$property])==0 && !$returnblanks) {
                        return $default;
                    } else {
                        return $_GET[$property];
                    }
                }
            }
        }

        
   /*  ============================================
        FUNCTION:   kvp (STATIC)
        PARAMS:     kvp    - the key/value pair array to search
                    property - the property of the array to return
                    default  - the default value to return in case of error
                    returnblanks - (boolean) should we return a blank or the default value 
                              if the found property value is blank
        RETURNS:    value
        ============================================  */
        public static function kvp($kvp, $property, $default="", $returnblanks=true) {

            if (!isset($kvp)) {
                return $default;   
            } else {
                if (!isset($kvp[$property])) {
                    return $default;   
                } else {
                    if (!is_array($kvp[$property]) && strlen($kvp[$property])==0 && !$returnblanks) {
                        return $default;
                    } else {
                        return $kvp[$property];
                    }
                }
            }
        }
        
        

    }

