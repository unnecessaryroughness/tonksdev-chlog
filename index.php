<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $frontpgctrl = new chlog\Frontpage_Control();

    //Process the form action & store the resulting view object 
    $vw = $frontpgctrl->process(chlog\safeget::post("action", "unset", false), $_POST); 

    //extract the page title & content from the view
    $pgtitle = $vw->title();
    $pgcontent = $vw->html();

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    