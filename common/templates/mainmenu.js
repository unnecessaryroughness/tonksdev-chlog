function toggleMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu").slideToggle(100);   
    $("#chlog-mainmenu>ul>li").removeClass("chlog-mainmenu-highlighted");
}

function openMegaMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu>ul>li").removeClass("chlog-mainmenu-highlighted");
    
    if ($(this).attr("submenu") != undefined) {
        $(this).addClass("chlog-mainmenu-highlighted");
        $("#"+$(this).attr("submenu")).delay(1000).show();
    } 
}

function closeMegaMenu() {
    $(this).hide();   
    $("#chlog-mainmenu>ul>li").removeClass("chlog-mainmenu-highlighted");
}


/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openMegaMenu);
    $(".chlog-submenu").bind("mouseleave", closeMegaMenu);
});


