<?php

include "define.php";


include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';

sec_session_start();

  $result = NULL;
  if (isset($_GET["id"])) {
    if ($query = $mysqli->prepare("SELECT timestamp,page_title,post,public FROM content WHERE id=?")) {
      $query->bind_param("i", $_GET["id"]);
      if ($query->execute()) {
        $result = $query->get_result()->fetch_array(MYSQLI_ASSOC);
        $sections = json_decode($result["post"], true);
        if ($result == NULL) {
          header("Location: ../error?status=404");
          exit();
        }
      } else {
        header("Location: ../error?status=500");
        exit();
      }
      $query->close();
    } else {
      header("Location: ../error?status=500");
      exit();
    }
  } else {
    header("Location: ../error?status=400");
    exit();
  }

if (!login_check($mysqli) && intval($result["public"]) == 0) {
  header("Location: ../error?status=401");
  exit();
}


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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Yantramanav:100,300,400,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/general.css" />
  <link rel="stylesheet" type="text/css" href="css/page.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script src="js/general.js"></script>
  <script src="js/page.js"></script>
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel='stylesheet' type='text/css' href='css/phpstyles.php' />
  <title><?php
      echo $result["page_title"];
  ?></title>
</head>
<body>
  <?php include ROOT . "/site/page_header.php";?>
  
  <?php if (login_check($mysqli)) {
    echo "<a class='edit_button' title='Edit' href='edit/content?id=" . $_GET["id"] . "'><img src='svg/edit.svg'></a>";
  }?>
  
  <div class='post_sections'>
    <?php
      foreach($sections as $section) {
        echo "<div class='post_section'>";
        echo "<div class='post_title'>" . $section["title"] . "</div>";
        echo "<div class='section_desc'>" . $section["desc"] . "</div>";
        echo "<div class='section_content'>";
        foreach ($section["content"] as $content) {
          echo "<div class='content_block'>";
          if (isset($content["desc"])) {
            echo "<img src='https://nogelai-net-santerinogelainen.c9users.io/svg/add_white.svg' class='cb_info_button'>";
            echo "<span class='cb_info_desc'><h3>" . $content["title"] . "</h3><img class='cb_info_button cb_black' src='https://nogelai-net-santerinogelainen.c9users.io/svg/add_white_thin.svg'>" . $content["desc"] . "</span>";
          }
          echo "<a href='" . $content["href"] . "'>";
          echo "<div class='content_pic' style='background-image: url(\"" . $content["thumbnail"] . "\")'></div>";
          echo "<h3 class='content_name'>" . $content["title"] . "</h3>";
          echo "</a>";
          echo "</div>";
        }
        echo "</div>";
        echo "</div>";
      }
    ?>
  </div>
  
  <div class='date image_date'><?php
      if ($result["public"] == 0) {
        echo "<span style='color: white;'>Draft </span>";
      }
      echo "Last updated " . $result["timestamp"] . " UTC";
    ?>
  </div>
  
  <?php
  include ROOT . "/site/footer.php";
  ?>
</body>
</html>