<?php 


    //load config
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/config.php";

    //load home page content into $pgcontent
    //Each page ".php" file will replicate this basic structure. 
    //Create the class that controls this page and use its methods to
    //generate the required HTML output.
    $pgtitle = "chLOG Home Page";
    $pgcontent = "<p>Front Page</p>";


    //load main layout template
    require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/layout.html.php";
    
?>
