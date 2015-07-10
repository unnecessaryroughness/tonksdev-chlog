<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $aboutctrl = new chlog\About_Control();

    //Process the form action & store the resulting view object 
    $vw = $aboutctrl->process("about", $_GET); 

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    