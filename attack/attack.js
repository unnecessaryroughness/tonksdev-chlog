//onload
$(function() {
    resetKipShading();
    
    $("#kipscale td").each(function() {
        $(this).on("click", function() {
            $("#rngLevel").val($(this).text());
            resetKipShading();
        });
    });
    
});


function resetKipShading() {
    $("#kipscale td").each(function() {
        $(this).css("background-color", "rgba(255, 0, 0, " + ($(this).text() * 0.1) + ")"); 
        $(this).css("color", "#000"); 
        
        if ($(this).text() == $("#rngLevel").val()) {
            $(this).css("background-color", "#3a515f");
            $(this).css("color", "#ede859");
        }
    });
}
                           