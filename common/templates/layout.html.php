<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
	<meta name="apple-mobile-web-app-title" content="chLOG">
	<link rel="apple-touch-icon" sizes="180x180" href="/common/chLog180.png">
    <script src="/jquery/jquery-1.11.3.min.js"></script>
  
    <link rel="stylesheet" href="/common/styles/chlog-style-main.css">
    <?php 
        $cssfiles = $vw->css();
        if (is_array($cssfiles)) {
            foreach ($cssfiles as $css) {
                echo (($css) ? "<link rel='stylesheet' href='".$css."'>" : "");        
            }
        } else {
            echo (($cssfiles) ? "<link rel='stylesheet' href='".$cssfiles."'>" : ""); 
        }
    ?>
      
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

