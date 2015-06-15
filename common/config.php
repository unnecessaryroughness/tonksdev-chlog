<?php 

    //Include common functions
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/functions.php";

    //set up session & configuration - has to happen in every page
    safesessionstart();

    //Add environment object
    $_SESSION["environment"] = new chlog\Environment();

?>
