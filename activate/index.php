<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //Create login controller
    $activatectrl = new chlog\Activate_Control();

    //Process the form action & store the resulting view object 
    $vw = $activatectrl->process("activate", $_GET); 

    //extract the page title & content from the view
    $pgtitle = $vw->title();
    $pgcontent = $vw->html();

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    