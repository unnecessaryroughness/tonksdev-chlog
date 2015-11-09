/*
===============================================================
calendar.js
===============================================================
Reusable calendar component - supporting Javascript functions
---------------------------------------------------------------
*/

//CLASS DEFINITION
function Chlog_Calendar(id, ctrl) {
    this.id = id;
    this.rendered = false;
    this.boundcontrol = ctrl;
    
    this.monthlist = ["January", "February", "March", "April", "May", "June", "July",
                      "August", "September", "October", "November", "December"];
  
    //populate the calendar with current month -1
    this.populatePrevious = function() {
        var y = (this.month -1) >= 0 ? this.year : this.year -1;
        var m = (this.month -1) >= 0 ? this.month -1 : 11;
        this.populateCalendar(y, m);
    };

    //populate the calendar with current month +1
    this.populateNext = function() {
        var y = (this.month +1) <= 11 ? this.year : this.year +1;
        var m = (this.month +1) <= 11 ? this.month +1 : 0;
        this.populateCalendar(y, m);
    };
                                         
    //function to populate the calendar. 
    //Parameters supply the required year & month (base-0)
    this.populateCalendar = function (year, month) {
        this.month = month;
        this.year = year;
        this.today = new Date();
        this.today.setHours(0, 0, 0, 0);
        this.cellDateXref = [];

        $("#" + this.id + "_thMonthName").html(this.monthlist[this.month] + " " + this.year);

        //calculate the day of the week for the 1st day of the month and store in this.firstDOM
        var date = new Date(this.year, this.month, 1);
        this.firstDOM = date.getDay();
        
        //calculate how many total days there are in the month & store to this.lastDOM
        if (["3", "5", "8", "10"].indexOf(this.month.toString()) !== -1) {
            this.lastDOM = 30;
        } else if (["1"].indexOf(this.month.toString()) !== -1) {
            if (new Date(this.year, 1, 29).getMonth() === 1) {
                this.lastDOM = 29;
            } else {
                this.lastDOM = 28;
            }
        } else {
            this.lastDOM = 31;
        }

        $("#" + this.id + " .tCell").removeClass("tdCalToday").removeClass("tdCalSelected");

        var r, c;
        var cellVal = "";
        var currDate = 0;
        
        //loop around every row (r) and every column (c) in the calendar
        for (r=1; r<=6; r++) {
            for (c=0; c<=6; c++) {

                //Determine which date number to put in the current cell
                //Use blanks if before first day of month, or after last day of month
                if ((r===1 && c < this.firstDOM) || currDate >= this.lastDOM) {
                    cellVal = "";
                } else {
                    currDate += 1;
                    cellVal = currDate;
                }

                thisCellId = "#" + this.id + "_c" + r + "-" + c;
                $(thisCellId).html(cellVal);
                this.cellDateXref.push({id: thisCellId, date: cellVal});
                
                //Set the year, month & day as attributes of the cell 
                $(thisCellId).attr("year", this.year);
                $(thisCellId).attr("month", this.month);
                $(thisCellId).attr("day", cellVal);

                //Set style if this cell contains today's date
                if (cellVal !== "") {
                    var dCell = new Date(this.year, this.month, currDate);
                    dCell.setHours(0, 0, 0, 0);

                    //the "+" operator turns the dates into a numeric 
                    if (+dCell == +this.today) {
                        $(thisCellId).addClass("tdCalToday"); 
                    }
                }
                
                //Set CLICK event of this cell. 
                $(thisCellId).on("click", function () {
                    if ($(this).html() !== "") {
                        sDate = $(this).attr("year") + "-" + 
                                    ("00" + (parseInt($(this).attr("month"))+1)).slice(-2) + "-" +
                                    ("00" + $(this).attr("day")).slice(-2)
                        
                        var pID = $(this).attr("parentid");
                        $("#" + pID + " .tCell").removeClass("tdCalSelected");
                        $(this).addClass("tdCalSelected");                        
                        $("#" + pID + "_txtCalDate").val(sDate);
                        $("#" + pID + "_txtLastDate").val(this.id);
                        $("#" + pID).trigger("caldate:change");
                    }
                });  //click event
 
            } //inner loop
        } //outer loop 
    }; //populateCalendar 

    
    //function to find required date string value in the 
    //Xref array and fire the CLICK event of that cell
    this.highlightDate = function(dDateString) {
        
        //1. get year & month from date parameter
        var lYear = parseInt(dDateString.substring(0, 4));
        var lMonth = parseInt(dDateString.substring(5, 7))-1;
        var lDay = parseInt(dDateString.substring(8,10));
        var lTargetCell = null;
        
        //2. repopulate calendar from year & month
        this.populateCalendar(lYear, lMonth);
        
        //3. find date value in the cross-reference table
        //   and store the target cell ID
        for (var iCell = 0; iCell < this.cellDateXref.length; iCell++) {
            if (this.cellDateXref[iCell].date == lDay) {
                lTargetCell = this.cellDateXref[iCell].id;
                break;
            }
        }

        //4. fire the click event of the cell, using the found ID.
        if (lTargetCell != null) {
            $(lTargetCell).click();
        }
    }

    
    this.renderMe = function() {
        var sRender = '\
        <div id="{nm}" class="dtpCalendar"> \
            <table class="tblCalendar"> \
                <tr> \
                    <th class="tCell thCalHeader" id="{nm}_thMonthPrev"><</th> \
                    <th class="tCell thCalHeader" colspan="5" id="{nm}_thMonthName">Month</th> \
                    <th class="tCell thCalHeader" id="{nm}_thMonthNext">></th> \
                </tr> \
                <tr> \
                    <th class="tCell thCalHeader">S</th> \
                    <th class="tCell thCalHeader">M</th> \
                    <th class="tCell thCalHeader">T</th> \
                    <th class="tCell thCalHeader">W</th> \
                    <th class="tCell thCalHeader">T</th> \
                    <th class="tCell thCalHeader">F</th> \
                    <th class="tCell thCalHeader">S</th> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c1-6"></td> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c2-6"></td> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c3-6"></td> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c4-6"></td> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c5-6"></td> \
                </tr> \
                <tr> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-0"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-1"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-2"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-3"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-4"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-5"></td> \
                    <td class="tCell tdCalDay" parentid="{nm}" id="{nm}_c6-6"></td> \
                </tr> \
            </table>  \
            <div id="hiddenFields"> \
                <input type="hidden" parentid="{nm}" name="prefix" value="{nm}"> \
                <input type="hidden" parentid="{nm}" name="{nm}_txtCalDate" id="{nm}_txtCalDate"> \
                <input type="hidden" parentid="{nm}" name="{nm}_txtLastDate" id="{nm}_txtLastDate"> \
            </div> \
        </div>'
    
        sRender = sRender.replace(/{nm}/g, this.id);
        this.rendered = true;
        return sRender;
    }
    
    this.setEventHandlers = function() {
        var o = this;
        var bdctrl = this.boundcontrol;
        $("#" + this.id + "_thMonthPrev").on("click", function() { o.populatePrevious(); });
        $("#" + this.id + "_thMonthNext").on("click", function() { o.populateNext(); });
        $("#" + this.id).on("caldate:change", function() { 
            if (bdctrl) {
                var dtctrl = $("#" + this.id + "_txtCalDate");  
                $(bdctrl).val($(dtctrl).val());
            }
            $(this).hide(); 
        });
    }
    
}
