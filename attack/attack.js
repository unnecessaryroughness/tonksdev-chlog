//onload
$(function() {
    
    //Initialise & configure calendar 1 (start date)
    var c1 = new Chlog_Calendar("c1");
    c1.populateCalendar(2015, 07);
    
    $("#c1_thMonthPrev").on("click", function() { c1.populatePrevious(); });
    $("#c1_thMonthNext").on("click", function() { c1.populateNext(); });
    
    $("#c1").on("caldate:change", function() {
        $("#txtStartDate").val($("#c1_txtCalDate").val());
    });
    
    $("#txtStartDate").on("change", function() {
        c1.highlightDate($(this).val());
    });
    
    //Initialise & configure calendar 2 (end date)
    var c2 = new Chlog_Calendar("c2");
    c2.populateCalendar(2015, 07);
    
    $("#c2_thMonthPrev").on("click", function() { c2.populatePrevious(); });
    $("#c2_thMonthNext").on("click", function() { c2.populateNext(); });
    
    $("#c2").on("caldate:change", function() {
        $("#txtEndDate").val($("#c2_txtCalDate").val());
    });
    
    $("#txtEndDate").on("change", function() {
        c2.highlightDate($(this).val());
    });
    
});
