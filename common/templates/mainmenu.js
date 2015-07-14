function toggleMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu").slideToggle(100);   
}

function openMegaMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu>ul").css("border-bottom", "1px solid #666");
    $("#chlog-mainmenu>ul>li").css("background-color", "#fff");
    
    if ($(this).attr("submenu") != undefined) {
        $(this).css("background-color", "#eef");
        $("#chlog-mainmenu>ul").css("border-bottom", "0px");
        $("#"+$(this).attr("submenu")).show();
    } 
}

function closeMegaMenu() {
    $("#chlog-mainmenu>ul").css("border-bottom", "1px solid #666");
    $("#chlog-mainmenu>ul>li").css("background-color", "#fff");
    $(this).hide();   
}


/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openMegaMenu);
    $(".chlog-submenu").bind("mouseleave", closeMegaMenu);
});


