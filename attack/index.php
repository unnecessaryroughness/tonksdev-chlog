<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $attackctrl = new chlog\Attack_Control();

    if (chlog\safeget::get("id", null, false)) {
        $vw = $attackctrl->process("review", $_GET); 
    } else {
        $vw = $attackctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 
    }

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    