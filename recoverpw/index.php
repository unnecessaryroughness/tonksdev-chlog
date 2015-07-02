<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $recoverctrl = new chlog\RecoverPW_Control();

    if (chlog\safeget::get("rid", null, false)) {
        $vw = $recoverctrl->process("changepw", $_GET); 
    } else {
        //Process the form action & store the resulting view object 
        $vw = $recoverctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 
    }

    //extract the page title & content from the view
    $pgtitle = $vw->title();
    $pgcontent = $vw->html();

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    