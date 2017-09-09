<?php

include "define.php";

include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';

sec_session_start();

  $result = NULL;
  if (isset($_GET["id"])) {
    if ($query = $mysqli->prepare("SELECT timestamp,page_title,post,public FROM imageposts WHERE id=?")) {
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
    echo "<a class='edit_button' title='Edit' href='edit/image?id=" . $_GET["id"] . "'><img src='svg/edit.svg'></a>";
  }?>
  
  <div class='post_sections'>
    <?php 
      foreach($sections as $section) {
        echo "<div class='post_section'>";
          echo "<div class='post_title'>" . $section["title"] . "</div>";
          echo "<div class='section_desc'>" . $section["desc"] . "</div>";
          echo "<div class='section_images'>";
          foreach ($section["images"] as $image) {
            echo "<div data-desc='" . $image["desc"] . "' data-title='" . $image["title"] . "' class='thumbnail_image' onclick='openImage(this, event)' onwheel='zoomImage(this)'>";
            echo "<img src='" . $image["thumbnail"] . "' class='image_post_thumbnail' data-path='" . $image["url"] . "'>";
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
  
  
  
  <div class="lightbox"></div>
  <img src="../svg/close_white.svg" class='lightbox_button close_lightbox' onclick="closeImage(true);" />
  <img src="../svg/right_white.svg" class='lightbox_button lightbox_right' onclick="rightImage();" />
  <img src="../svg/left_white.svg" class='lightbox_button lightbox_left' onclick="leftImage();" />
  <img src="../svg/plus.svg" class='lightbox_button lightbox_zoomin' onclick="zoominImage();" />
  <img src="../svg/minus.svg" class='lightbox_button lightbox_zoomout' onclick="zoomoutImage();" />
  <img src="../img/background.png" class='lightbox_button lightbox_background_grid' onclick="setBackgroundImage(this);" />
  <div class='lightbox_button lightbox_background_black' onclick="setBackgroundImage(this);"></div>
  <div class='lightbox_button lightbox_background_white' onclick="setBackgroundImage(this);"></div>
  <input type='text' disabled='disabled' class='lightbox_button lightbox_title'/>
  <div class='lightbox_button lightbox_desc'></div>
  
  <?php
  include ROOT . "/site/footer.php";
  ?>
  
</body>
</html>
