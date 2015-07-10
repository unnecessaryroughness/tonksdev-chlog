<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $activatectrl = new chlog\Activate_Control();

    //Process the form action & store the resulting view object 
    $vw = $activatectrl->process("activate", $_GET); 

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    