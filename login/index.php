<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //Create login controller
    $loginctrl = new chlog\Login_Control(new chlog\Login_View());

    //Process the form action 
    try {
        $pgcontent = $loginctrl->process(chlog\safeget::post("action", "unset", false), $_POST);   
    } catch (\Exception $e) {
        $errview = new chlog\Error();
        $pgcontent = $errview->html($e->getMessage());    
    }

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    
?>
