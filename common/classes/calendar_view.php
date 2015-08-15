<?php

    namespace chlog;
    
    class Calendar_View extends ChlogView {

        public $calname = null;
        
        public function __construct($cname = null) {
            $this->calname = ($cname) ? $cname : "u".rand(0, 65535);
        }
        
        public function html() {
            $nm = $this->calname;
            
            return <<<HTML

<div id="{$nm}">

    <table class="tblCalendar">
        <tr>
            <th class="tCell thCalHeader" id="{$nm}_thMonthPrev"><</th>
            <th class="tCell thCalHeader" colspan="5" id="{$nm}_thMonthName">Month</th>
            <th class="tCell thCalHeader" id="{$nm}_thMonthNext">></th>
        </tr>
        <tr>
            <th class="tCell thCalHeader">S</th>
            <th class="tCell thCalHeader">M</th>
            <th class="tCell thCalHeader">T</th>
            <th class="tCell thCalHeader">W</th>
            <th class="tCell thCalHeader">T</th>
            <th class="tCell thCalHeader">F</th>
            <th class="tCell thCalHeader">S</th>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c1-6"></td>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c2-6"></td>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c3-6"></td>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c4-6"></td>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c5-6"></td>
        </tr>
        <tr>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-0"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-1"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-2"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-3"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-4"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-5"></td>
            <td class="tCell tdCalDay" parentid="{$nm}" id="{$nm}_c6-6"></td>
        </tr>
    </table>
    <div id="hiddenFields">
        <input type="hidden" parentid="{$nm}" name="prefix" value="{$nm}">
        <input type="hidden" parentid="{$nm}" name="{$nm}_txtCalDate" id="{$nm}_txtCalDate">
        <input type="hidden" parentid="{$nm}" name="{$nm}_txtLastDate" id="{$nm}_txtLastDate">
    </div>
</div>

HTML;
        }
        
        public function title() {
            return "chLOG Calendar Component";   
        }
        
        public function css() {
            return "/common/calendar/chlog-calendar.css";   
        }
    }
