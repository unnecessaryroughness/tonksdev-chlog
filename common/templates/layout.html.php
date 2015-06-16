<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
<!--	<meta name="apple-mobile-web-app-title" content="CHlog">-->
<!--	<link rel="apple-touch-icon" sizes="72x72" href="/chlog/common/images/chlog.png">-->
    <script src="/chlog/jquery/jquery-1.11.3.min.js"></script>
  
    <link rel="stylesheet" href="/chlog/common/styles/chlog-style-main.css">
    <?php echo (isset($pgcss)) ? $pgcss : "" ?>
    <title><?php htmlout((isset($pgtitle)) ? $pgtitle : "untitled page") ?></title>
  </head>
  <body>
      <div id="chlog-container">
          <?php require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/header.html.php" ?>
          <?php require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/mainmenu.html.php" ?>

          <!-- Include the main content -->
          <?php echo (isset($pgcontent)) ? $pgcontent : "" ?>
          
          <?php require $_SERVER["DOCUMENT_ROOT"]."/chlog/common/templates/footer.html.php" ?>
      </div>
  </body>
</html>

