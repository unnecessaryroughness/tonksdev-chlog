<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //Create login controller
    $adminctrl = new chlog\Useradmin_Control();

    //Process the form action & store the resulting view object 
    $vw = $adminctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 

    //extract the page title & content from the view
    $pgtitle = $vw->title();
    $pgcontent = $vw->html();

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    