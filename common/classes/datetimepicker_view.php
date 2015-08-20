<?php

    namespace chlog;
    
    class Datetimepicker_View extends ChlogView {

        public $dtpname = null;
        public $dtpcaption = "";
        
        public function __construct($dtpname = null, $dtpcaption = "Date/Time") {
            $this->dtpname = ($dtpname) ? $dtpname : "u".rand(0, 65535);
            $this->dtpcaption = $dtpcaption;
        }
        
        public function html($default = null) {
            $nm = $this->dtpname;
            $cnm = $nm."_cal";
            $cal1 = new calendar_view($cnm);
            
            if ($default) {
                $ddateobj = new \DateTime($default);
            } else {
                $ddateobj = new \DateTime();
            }
            $deffull = $ddateobj->format("Y-m-d H:i");
            $defdate = $ddateobj->format("Y-m-d");
            $defhrs = $ddateobj->format("H");
            $defmin = $ddateobj->format("i");
            
            return <<<HTML
            
    <div id="{$nm}">

        <label for="txtStartDate">{$this->dtpcaption}</label>
        <input type="text" id="{$nm}_txtStartDate" name="{$nm}_txtStartDate" 
                class="fldMedium" value="{$defdate}">@<select id="{$nm}_selStartHour" 
                name="{$nm}_selStartHour" class="timeDropdown"">
                </select>:<select id="{$nm}_selStartMin" name="{$nm}_selStartMin" 
                class="timeDropdown"></select>
                
        {$cal1->html()}

        <div id="hiddenFields">
            <input type="hidden" name="{$nm}_txtFullDateTime" 
                    id="{$nm}_txtFullDateTime" value="{$deffull}">
        </div>
    </div>

    <script language="javascript">
        
        $(function() {
            var i = 0;
            for (i=0; i<=23; i++) {
                $("#{$nm} #{$nm}_selStartHour").append("<option" + (i=={$defhrs} ? " selected" : "") + ">" + (("00" + i).slice(-2)) + "</option>"); 
            }
            for (i=0; i<=59; i++) {
                $("#{$nm} #{$nm}_selStartMin").append("<option " + (i=={$defmin} ? " selected" : "") + ">" + (("00" + i).slice(-2)) + "</option>"); 
            }
            var c1 = new Chlog_Calendar("{$cnm}");
            var dToday = new Date();
            c1.populateCalendar(dToday.getFullYear(), dToday.getMonth());
            
            $("#{$cnm}_thMonthPrev").on("click", function() { c1.populatePrevious(); });
            $("#{$cnm}_thMonthNext").on("click", function() { c1.populateNext(); });

            $("#{$cnm}").on("caldate:change", function() {
                $("#{$nm}_txtStartDate").val($("#{$cnm}_txtCalDate").val());
                $("#{$nm}_txtFullDateTime").val({$nm}_genFullDT());
                $("#{$nm}_selStartHour").focus();
            });
            
            $("#{$nm}_txtStartDate").on("change", function() {
                c1.highlightDate($(this).val());
                $("#{$nm}_txtFullDateTime").val({$nm}_genFullDT());
            });
            
            $("#{$nm}_txtStartDate").on("focus", function() {
                $("#{$cnm}").slideDown();
            });
            
            $("#{$nm}_txtStartDate").on("blur", function() {
                $("#{$cnm}").slideUp();
            });
            
            $("#{$nm}_selStartHour").on("change", function() {
                $("#{$nm}_txtFullDateTime").val({$nm}_genFullDT());
            });
            
            $("#{$nm}_selStartMin").on("change", function() {
                $("#{$nm}_txtFullDateTime").val({$nm}_genFullDT());
            });
        });
        
        function {$nm}_genFullDT() {
            return $("#{$nm}_txtStartDate").val() + " " + 
                      $("#{$nm}_selStartHour").val() + ":" +
                      $("#{$nm}_selStartMin").val() + ":00";
        }
        
    </script>

HTML;
        }
        
        public function title() {
            return "chLOG Calendar Component";   
        }
        
        public function css() {
            return "/common/calendar/chlog-calendar.css";   
        }
    }
