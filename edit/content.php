<?php

//edit / add content page

include "../define.php";


include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';
include ROOT . '/includes/addmenu.php';

sec_session_start();


if (login_check($mysqli)):
  
  function getNextAutoIncrement($mysqli) {
    if ($query = $mysqli->query("SELECT MAX(id) FROM content;")) {
      $newid = $query->fetch_array(MYSQLI_NUM);
      $newid = $newid[0] + 1;
      $query->close();
      return $newid;
    } else {
      return false;
    }
  }

  $error = "";
  $exists = false;
  $result = NULL;
  if (isset($_GET["id"])) {
    if ($query = $mysqli->prepare("SELECT page_title,post,public FROM content WHERE id=?")) {
      $query->bind_param("i", $_GET["id"]);
      if ($query->execute()) {
        $result = $query->get_result()->fetch_array(MYSQLI_ASSOC);
        $sections = json_decode($result["post"], true);
        $exists = true;
        if ($result == NULL) {
          $error = "Post " . $_GET["id"] . " does not exist.";
          $newid = getNextAutoIncrement($mysqli);
          $exists = false;
        }
      } else {
        $error = "MySQL execution error!";
      }
      $query->close();
    } else {
      $error = "Query preparation error.";
    }
  } else {
    $newid = getNextAutoIncrement($mysqli);
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
  <link rel="stylesheet" type="text/css" href="../css/general.css" />
  <link rel="stylesheet" type="text/css" href="../css/page.css" />
  <link rel="stylesheet" type="text/css" href="../css/content.css" />
  <link rel="stylesheet" type="text/css" href="../css/window.css" />
  <link rel="stylesheet" type="text/css" href="../css/filesystem.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script src="../js/general.js"></script>
  <script src="../js/page.js"></script>
  <script src="../js/window.js"></script>
  <script src="../js/filesystem.js"></script>
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
  <link rel='stylesheet' type='text/css' href='../css/temp_phpstyles.php' />
  <title><?php
    if ($result !== NULL) {
      echo $result["page_title"];
    } else {
      echo "Content page";
    }
  ?></title>
</head>
<body>
  <div class='post_data' <?php
    if (isset($_GET["id"]) && $exists) {
      echo "data-id='" . $_GET["id"] . "' ";
      echo "data-public='" . $result["public"] . "' ";
    } else {
      echo "data-id='" . $newid . "' ";
      echo "data-public='0' ";
    }
  ?>></div>
  <?php include ROOT . "/site/page_header.php";?>
  
  <img class="post_settings_btn" src="../svg/gear_white.svg" />
  
  <div class='post_sections'>
    
    <?php if (!$exists): ?>
    
    <div class="post_section">
      <input type='text' class='post_title' placeholder='Title'>
      <div class='section_desc' contenteditable spellcheck='false'></div>
      <div class='section_content'></div>
    </div>
    
    <?php 
    else: 
      foreach($sections as $section) {
        echo "<div class='post_section'>";
        echo "<input type='text' class='post_title' placeholder='Title' value='" . $section["title"] . "'>";
        echo "<div class='section_desc' contenteditable spellcheck='false'>" . $section["desc"] . "</div>";
        echo "<div class='section_content'>";
        foreach ($section["content"] as $content) {
          echo "<div class='content_block' data-title='" . $content["title"] . "' data-href='" . $content["href"] . "' data-thumbnail='" . $content["thumbnail"] . "' ";
          if (isset($content["desc"])) {
            echo "data-desc='" . $content["desc"] . "'";
          }
          echo "><img src='https://nogelai-net-santerinogelainen.c9users.io/svg/close_white.svg' class='remove_content'/>";
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
    endif;
    ?>
    
    <img src='/svg/add_white.svg' class='new_section' title='New section'>
  </div>
  
  <?php
  
    $menu = new AddMenu("", $mysqli, true, true);
    echo $menu->html();
  
  ?>
  
  <div class='window' id="post_settings">
    <form>
      <img src="../svg/close.svg" class='close_window' />
      <h2>Settings:</h2>
      Custom page title?: <input type="checkbox" class="custom_page_title" <?php
      if ($result !== NULL && $sections[0]["title"] != $result["page_title"]) {
        echo "checked='checked'";
      }
      ?>/><br />
      Page title: <input type="text" class="page_title" <?php
      if ($result !== NULL) {
        echo "value='" . $result["page_title"] . "'";
      } else {
        echo "disabled='disabled'";
      }
      ?> /><br />
    </form>
  </div>
  
  <div class="save_block">
    <div class="save_draft" data-public="0">Save as Draft</div>
    <div class="save_publish" data-public="1">Publish</div>
  </div>
  
  <?php
  include ROOT . "/site/footer.php";
  ?>
  <script src="../js/content.js"></script>
  <?php
    if ($error != "") {
      echo "<script>toastMsg('$error');</script>";
    }
  ?>
</body>
</html>

<?php
else:
  header("Location: ../error?status=403");
  exit;
endif;
?>
