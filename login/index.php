<?php 

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //Create login view class
    $loginview = new chlog\Login();

    //Show form
    try {
        $pgtitle = $loginview::pgtitle;
        $pgcontent = $loginview->handleResponse(chlog\safeget::post("action", "unset", false), $_POST);
    } catch (\Exception $e) {
        $errview = new chlog\Error();
        $pgcontent = $errview->html($e->getMessage());
    }

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    
?>
