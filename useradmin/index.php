<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $adminctrl = new chlog\Useradmin_Control();

    //Process the form action & store the resulting view object 
    $vw = $adminctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    