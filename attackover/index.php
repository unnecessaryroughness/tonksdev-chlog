<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $attackctrl = new chlog\AttackOver_Control();

    $vw = $attackctrl->process("over", $_POST); 

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    