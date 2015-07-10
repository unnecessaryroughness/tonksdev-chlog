function toggleMenu() {
    $("#chlog-mainmenu").children("ul").find("ul").slideUp(100);
    $("#chlog-mainmenu").slideToggle(100);   
}

function toggleSubMenu(obj) {
    $(obj).siblings().find("ul").slideUp(100);
    $(obj).children("ul").slideToggle(100);
}

function openSubMenu() {
    $("#chlog-mainmenu>ul").find("ul").css({visibility: "hidden"});
    $(this).children("ul").css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0}, 300);   
}

function closeSubMenu() {
    $(this).find("ul").css({visibility: "hidden"});
}

/* IMMEDIATE SCRIPTS */

$(document).ready(function() {
    $("#chlog-mainmenu>ul>li").bind("mouseenter", openSubMenu);
    $("#chlog-mainmenu>ul").bind("mouseleave", closeSubMenu);
});


