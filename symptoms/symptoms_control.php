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

            if (parent::notLoggedIn()) {
                return parent::notLoggedIn();
            }
            
            switch ($type) {
                                
                case "update":
                    $tsl = new LookupList("symptom", safeget::kvp($fields, "jsoString", "", false));
                    $eml = safeget::session("user", "email", null);

                    try {
                        Lookups::updateLookupList($eml, "Symptom", $tsl);
                    } catch (\Exception $e) {
                        Logger::log("Failed trying to update symptoms from JSON object. ", $e->getMessage());
                        return new Error_View($e->getCode(), getNiceErrorMessage($e));
                    }
                
                    return new Redirect_View("/symptoms/");
                    break;
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    $eml = safeget::session("user", "email", null);
                    $sl = Lookups::getLookupList($eml, "Symptom");
                    return new Symptoms_View($sl);
                    break;
            }
        }

    }

