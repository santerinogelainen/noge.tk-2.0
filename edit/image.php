<?php

//edit / add image post

include "../define.php";

include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';
include ROOT . '/includes/menu.php';

sec_session_start();


if (login_check($mysqli)):
  
  function getNextAutoIncrement($mysqli) {
    if ($query = $mysqli->query("SELECT MAX(id) FROM imageposts;")) {
      $newid = $query->fetch_array(MYSQLI_NUM);
      $newid = $newid[0] + 1;
      return $newid;
    } else {
      return false;
    }
  }

  $error = "";
  $exists = false;
  $result = NULL;
  if (isset($_GET["id"])) {
    if ($query = $mysqli->prepare("SELECT page_title,post,public FROM imageposts WHERE id=?")) {
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
  <link rel="stylesheet" type="text/css" href="../css/image.css" />
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
      echo "Image post";
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
  
  <img class="choose_images_btn" src="../svg/add_white.svg" />
  <img class="post_settings_btn" src="../svg/gear_white.svg" />
  
  <div class='post_sections'>
    
    <?php if (!$exists): ?>
    
    <div class="post_section">
      <input type='text' class='post_title' placeholder='Title'>
      <div class='section_desc' contenteditable spellcheck='false'></div>
      <div class='section_images'>
        
      </div>
      <div class="upload_reminder noselect">
        Drag images here.
      </div>
    </div>
    
    <?php 
    else: 
      foreach($sections as $section) {
        echo "<div class='post_section'>";
        echo "<input type='text' class='post_title' placeholder='Title' value='" . $section["title"] . "'>";
        echo "<div class='section_desc' contenteditable spellcheck='false'>" . $section["desc"] . "</div>";
        echo "<div class='section_images'>";
        foreach ($section["images"] as $image) {
          echo "<div data-desc='" . $image["desc"] . "' data-title='" . $image["title"] . "' class='thumbnail_image' onclick='openImage(this, event)' onwheel='zoomImage(this)'>";
          echo "<img src='" . $image["thumbnail"] . "' class='image_post_thumbnail' data-path='" . $image["url"] . "'><img src='/svg/close_white.svg' class='remove_image'></div>";
        }
        echo "</div>";
        echo "<div class='upload_reminder noselect' style='display: none;'>Drag images here.</div>";
        echo "</div>";
      }
    endif;
    ?>
    
    <img src='/svg/add_white.svg' class='new_section' title='New section'>
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
  <input type='text' class='lightbox_button lightbox_title' placeholder='Title (optional)'/>
  <div class='lightbox_button lightbox_desc' contenteditable spellcheck='false'></div>
  
  <div class="window choose_images_window">
    <form>
      <div class='window_section'>
        <h4>Section:</h4>
        <select>
          
        </select>
      </div>
      <img class="close_window" src="../svg/close.svg" />
        <?php
          $menu = new Menu();
          echo $menu->addFileSelection("Images:", array("checkbox", 150), "image");
        ?>
      <button type='button' class='window_submit_images'>Add</button>
    </form>
  </div>
  
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
  
  <div class="file_progress">
    <div class='file_progress_remaining'></div>
  </div>
  
  <div class="save_block">
    <div class="save_draft" data-public="0">Save as Draft</div>
    <div class="save_publish" data-public="1">Publish</div>
  </div>
  
  <?php
  include ROOT . "/site/footer.php";
  ?>
  <script src="../js/image.js"></script>
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
