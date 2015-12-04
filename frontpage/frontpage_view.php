<?php

    namespace chlog;
        
    class Frontpage_View extends ChlogView {

        protected $a1wData = [];
        protected $a1mData = [];
        protected $aTPData = [];
        
        
    /*  ============================================
        FUNCTION:   __construct
        PARAMS:     (none)
        RETURNS:    (object)
        PURPOSE:    constructs the class. No special functions.
        ============================================  */
        public function __construct($a1w, $a1m, $atp) {
            if ($a1w) { $this->a1wData = $a1w; }
            if ($a1m) { $this->a1mData = $a1m; }
            if ($atp) { $this->aTPData = $atp; }
        }

        
    /*  ============================================
        FUNCTION:   title
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate page title for the current state
        ============================================  */
        public function title() {
            return "chLOG - Online cluster headache tracking and reporting";
        }
        
        
    /*  ============================================
        FUNCTION:   html
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the appropriate HTML view for the current state
        ============================================  */
        public function html() {
            return $this->defaulthtml();
        }
        

    /*  ============================================
        FUNCTION:   defaulthtml
        PARAMS:     (none)
        RETURNS:    (string)
        PURPOSE:    returns the default HTML view 
        ============================================  */
        public function defaulthtml() {
            
            $jso1w = json_encode($this->a1wData);
            $jso1m = json_encode($this->a1mData);
            $jsotp = buildPlanObjJSO($this->aTPData);
            
            return <<<HTML
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  
            <p class="dashtitle">My Cluster Headache Dashboard<p>
            <div id="chlog-dashboard">
            
                <div id="chart-1" class="chlog-dashboard-chart">
                    <div class="chlog-dashboard-chart-title">Today's Planned Medication:</div>
                    
                    <div id="dashtreatments"></div>
                </div>
                <div id="chart-2" class="chlog-dashboard-chart"></div>
                <div id="chart-3" class="chlog-dashboard-chart"></div>
                <div id="chart-4" class="chlog-dashboard-chart"></div>
            
            </div>
            
            <script language="javascript">
                var jso1w = {$jso1w};
                var jso1m = {$jso1m};
                var jsoplan = {$jsotp};
            </script>
            
            <script language="javascript" src="/frontpage/frontpage.js"></script>
HTML;
        }

        public function css() {
            return "/frontpage/frontpage.css";   
        }
        
    }


    