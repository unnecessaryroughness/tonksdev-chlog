function toggleMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu").slideToggle(100);   
}

function openMegaMenu() {
    if (screen.width >= 620) {
        $(".chlog-submenu").fadeOut(400);
    } else {
        $(".chlog-submenu").hide();
    }
    $("#chlog-mainmenu>ul").css("border-bottom", "1px solid #666");
    $("#chlog-mainmenu>ul>li").css("background-color", "#fff").css("color", "#666");
    
    if ($(this).attr("submenu") != undefined) {
        $(this).css("background-color", "#0775ba").css("color", "#fff");
        $("#chlog-mainmenu>ul").css("border-bottom", "0px");
        if (screen.width >= 620) {
            $("#"+$(this).attr("submenu")).fadeIn(400);
        } else {
            $("#"+$(this).attr("submenu")).show();
        }
    } 
}

function closeMegaMenu() {
    $("#chlog-mainmenu>ul").css("border-bottom", "1px solid #666");
    $("#chlog-mainmenu>ul>li").css("background-color", "#fff").css("color", "#666");
    if (screen.width >= 620) {
        $(this).fadeOut(400);   
    } else {
        $(this).hide();   
    }
}


/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openMegaMenu);
    $(".chlog-submenu").bind("mouseleave", closeMegaMenu);
});


