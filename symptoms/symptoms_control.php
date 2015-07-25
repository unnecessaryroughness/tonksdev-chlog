<?php

    namespace chlog;

    class Symptoms_Control extends ChlogController {

    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none_
        RETURNS:    (object)
        PURPOSE:    constructs the class 
        ============================================  */
        public function __construct() {}
        
    /*  ============================================
        FUNCTION:   process
        PARAMS:     type    - the type of process that needs to be run
                    fields  - a key/value pair of field data to support the process
        RETURNS:    (string) HTML data
        PURPOSE:    returns the relevant view object for display
        ============================================  */
        public function process($type, $fields) {

            switch ($type) {
                
                case "update":
                    $tsl = new SymptomList(safeget::kvp($fields, "jsosymptoms", "", false));
                    $eml = safeget::session("user", "email", null);

                    try {
                        Lookups::updateSymptomsList($eml, $tsl);
                    } catch (\Exception $e) {
                        Logger::log("Failed trying to update symptoms from JSON object. ", $e->getMessage());
                        return new Error_View($e->getCode(), getNiceErrorMessage($e));
                    }
                
                    $sl = Lookups::getSymptomsList($eml);
                    return new Redirect_View("/symptoms/");
                    break;
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    $eml = safeget::session("user", "email", null);
                    $sl = Lookups::getSymptomsList($eml);
                    return new Symptoms_View($sl);
                    break;
            }
        }

    }

