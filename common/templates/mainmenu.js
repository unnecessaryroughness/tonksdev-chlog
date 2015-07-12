function toggleMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu").slideToggle(100);   
}

function openMegaMenu() {
    $(".chlog-submenu").hide();
    $("#"+$(this).attr("submenu")).show();
}

function closeMegaMenu() {
    $(this).hide();   
}


/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openMegaMenu);
    $(".chlog-submenu").bind("mouseleave", closeMegaMenu);
});


