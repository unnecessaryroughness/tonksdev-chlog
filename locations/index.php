<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $locationsctrl = new chlog\Locations_Control();

    //Process the form action & store the resulting view object 
    $vw = $locationsctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    