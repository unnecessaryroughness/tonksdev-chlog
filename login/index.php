<?php 

    use chlog as ch;

    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //Create login class
    $login = new ch\Login();

    //Show form
    $pgtitle = $login::pgtitle;
    $pgcontent = $login->handleResponse(ch\safeget::post("action", "unset", false), $_POST);

    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    
?>
