<?php

    namespace chlog;

    class SideEffects_Control extends ChlogController {

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
                    $tsl = new LookupList(safeget::kvp($fields, "jsoString", "", false));
                    $eml = safeget::session("user", "email", null);

                    try {
                        Lookups::updateLookupList($eml, "SideEffect", $tsl);
                    } catch (\Exception $e) {
                        Logger::log("Failed trying to update side effects from JSON object. ", $e->getMessage());
                        return new Error_View($e->getCode(), getNiceErrorMessage($e));
                    }
                
                    return new Redirect_View("/sideeffects/");
                    break;
                
                case "cancel":
                    return new Redirect_View("/");
                    break;
                
                default:
                    $eml = safeget::session("user", "email", null);
                    $sl = Lookups::getLookupList($eml, 'SideEffect');
                    return new SideEffects_View($sl);
                    break;
            }
        }

    }

