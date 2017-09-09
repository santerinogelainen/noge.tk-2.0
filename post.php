<?php

include "define.php";

include ROOT . '/includes/functions.php';

sec_session_start();

include ROOT . '/site/json.php';

$result = NULL;
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  if ($query = $mysqli->prepare("SELECT timestamp,title,page_title,post,public FROM posts WHERE id=?")) {
    $query->bind_param("i", $id);
    if ($query->execute()) {
      $result = $query->get_result()->fetch_array(MYSQLI_ASSOC);
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
  <link href="https://fonts.googleapis.com/css?family=Lobster|Playfair+Display|Raleway|Roboto|Ubuntu|VT323|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/page.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script src="js/general.js"></script>
  <script src="js/page.js"></script>
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <?php if (login_check($mysqli)) {
    echo "<link rel='stylesheet' type='text/css' href='css/temp_phpstyles.php' />";
  } else {
    echo "<link rel='stylesheet' type='text/css' href='css/phpstyles.php' />";
  }?>
  <title><?php
    if ($result !== NULL) {
      echo $result["page_title"];
    } else {
      echo "Post";
    }
  ?></title>
</head>
<body>
  <?php include ROOT . "/site/page_header.php";?>
  <form>
    <div class='block post_block'>
      <div class='post_title text_input'>
        <?php
          echo $result["title"];
        ?>
      </div>
      <div class='post_edit_settings'>
        <div class='post_textarea'>
          <div class='post_visual' spellcheck="false"><?php
              echo $result["post"];
          ?></div>
          <div class="lightbox">
          </div>
            <img src="svg/close_white.svg" class='lightbox_button close_lightbox' onclick="closeImage();" />
            <img src="svg/right_white.svg" class='lightbox_button lightbox_right' onclick="rightImage();" />
            <img src="svg/left_white.svg" class='lightbox_button lightbox_left' onclick="leftImage();" />
            <img src="svg/plus.svg" class='lightbox_button lightbox_zoomin' onclick="zoominImage();" />
            <img src="svg/minus.svg" class='lightbox_button lightbox_zoomout' onclick="zoomoutImage();" />
            <img src="img/background.png" class='lightbox_button lightbox_background_grid' onclick="setBackgroundImage(this);" />
            <div class='lightbox_button lightbox_background_black' onclick="setBackgroundImage(this);"></div>
            <div class='lightbox_button lightbox_background_white' onclick="setBackgroundImage(this);"></div>

            <?php
              if (login_check($mysqli)) {
                echo "<a class='edit_button' title='Edit' href='edit/post?id=$id'><img src='svg/edit.svg' /></a>";
              }
            ?>
          <div class='date'><?php
              if ($result["public"] == 0) {
                echo "<span style='color: white;'>Draft </span>";
              }
              echo "Last updated " . $result["timestamp"] . " UTC";
            ?>
          </div>
        </div>
      </div>
    </div>
  </form>
  <?php
  include ROOT . "/site/footer.php";
  ?>
</body>
</html>
