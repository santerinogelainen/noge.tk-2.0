<?php

include "define.php";

if (!isset($_COOKIE["cookie_approval"])) {
  setcookie("cookie_approval", 0, time() + (10 * 365 * 24 * 60 * 60), "/");
}

include ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
} else {
    $logged = 'out';
}

include ROOT . "/site/json.php";
?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="UTF-8">

    <!-- COLORS -->
    <meta name="theme-color" content="#000000" />
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-navbutton-color" content="#000000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000000">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/general.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">

    <!-- JS -->
    <script type="text/JavaScript" src="js/loadpage.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <script type="text/JavaScript" src="js/general.js"></script>
    <script type="text/JavaScript" src="js/login/sha512.js"></script>
    <script type="text/JavaScript" src="js/login/forms.js"></script>

    <!-- FAVICON BS -->
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">

    <!-- VIEWPORT -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- TITLE -->
    <title><?php echo $json->data->essentials->page_title; ?></title>

    <!-- PHP -->
    <?php
    if (login_check($mysqli)) {

      //CSS
      echo "<link rel='stylesheet' type='text/css' href='css/temp_phpstyles.php' />";
      echo "<link rel='stylesheet' type='text/css' href='css/filesystem.css' />";
      echo "<link rel='stylesheet' type='text/css' href='css/window.css' />";
      echo "<link rel='stylesheet' type='text/css' href='css/colorpicker.css' />";

      //JS
      echo "<script type='text/JavaScript' src='js/edit.js'></script>";
      echo "<script type='text/JavaScript' src='js/filesystem.js'></script>";
      echo "<script type='text/JavaScript' src='js/window.js'></script>";
      echo "<script type='text/JavaScript' src='js/colorpicker.js'></script>";
    } else {
      echo "<link rel='stylesheet' type='text/css' href='css/phpstyles.php' />";
    }
    ?>
  </head>
  <body>
    <?php include ROOT . "/includes/analyticstracking.php"; ?>
    <div id='page_loading'>
      <div class='loader'></div>
      <img class='loader_img' src='<?php echo $json->css->{'.loader_img'}->src;?>' />
    </div>
    <?php
      if (login_check($mysqli)) {
        include ROOT . "/includes/editmenu.php";
        include ROOT . "/includes/addmenu.php";
      }
      include ROOT . "/site/empty_space.php";
      include ROOT . "/site/header.php";
      include ROOT . "/site/hamburger.php";
    ?>

    <?php
    empty_space("front_page", true);
    include ROOT . "/site/information.php";
    empty_space("work_empty_space");
    include ROOT . "/site/work.php";
    empty_space("photography_art_empty_space");
    include ROOT . "/site/photography_art.php";
    empty_space("social_media_empty_space");
    include ROOT . "/site/social_media.php";
    include ROOT . "/site/footer.php";
    if (login_check($mysqli)) {
      include ROOT . "/includes/site_settings_window.php";
    }
    ?>

    <script src="js/index.js" defer></script>
    <?php
      $mysqli->close();
    ?>
  </body>
</html>
