<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/common/config.php";

    //Create login controller
    $aboutctrl = new chlog\About_Control();

    //Process the form action & store the resulting view object 
    $vw = $aboutctrl->process("about", $_GET); 

    //extract the page title & content from the view
    $pgtitle = $vw->title();
    $pgcontent = $vw->html();
    $pgcss = $vw->css();

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/common/templates/layout.html.php";
    