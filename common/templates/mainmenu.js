function toggleMenu() {
    $(".chlog-submenu").hide();
    $("#chlog-mainmenu").slideToggle(100);   
}

function openMegaMenu() {
    $(".chlog-submenu").hide();
    $("#"+$(this).attr("submenu")).fadeIn();
}

function closeMegaMenu() {
    $(this).fadeOut();   
}


/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openMegaMenu);
    $(".chlog-submenu").bind("mouseleave", closeMegaMenu);
});


