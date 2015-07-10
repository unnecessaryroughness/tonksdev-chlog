<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
<!--	<meta name="apple-mobile-web-app-title" content="CHlog">-->
<!--	<link rel="apple-touch-icon" sizes="72x72" href="/common/images/chlog.png">-->
    <script src="/jquery/jquery-1.11.3.min.js"></script>
  
    <link rel="stylesheet" href="/common/styles/chlog-style-main.css">
    <?php echo ($vw->css()) ? "<link rel='stylesheet' href='".$vw->css()."'>" : "" ?>
      
    <title><?php htmlout($vw->title() ? $vw->title() : "untitled page") ?></title>
  </head>
  <body>
      <div id="chlog-container">
          <?php require $_SERVER["DOCUMENT_ROOT"]."/common/templates/header.html.php" ?>
          <?php require $_SERVER["DOCUMENT_ROOT"]."/common/templates/mainmenu.html.php" ?>

          <!-- Include the main content -->
          <section id="chlog-main">
            <?php echo ($vw->html()) ? $vw->html() : "" ?>
          </section>
          
          <?php require $_SERVER["DOCUMENT_ROOT"]."/common/templates/footer.html.php" ?>
      </div>
  </body>
</html>

