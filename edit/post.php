<?php

include "../define.php";

include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';

sec_session_start();

include ROOT . '/includes/colorpicker.php';
include ROOT . '/includes/filesystem.php';


function getNextAutoIncrement($mysqli) {
  if ($query = $mysqli->query("SELECT MAX(id) FROM posts;")) {
    $newid = $query->fetch_array(MYSQLI_NUM);
    $newid = $newid[0] + 1;
    return $newid;
  } else {
    return false;
  }
}



if (login_check($mysqli)):
  $error = "";
  $exists = false;
  $result = NULL;
  if (isset($_GET["id"])) {
    if ($query = $mysqli->prepare("SELECT title,page_title,post,public FROM posts WHERE id=?")) {
      $query->bind_param("i", $_GET["id"]);
      if ($query->execute()) {
        $result = $query->get_result()->fetch_array(MYSQLI_ASSOC);
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

  <!-- VIEWPORT -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link href="https://fonts.googleapis.com/css?family=Yantramanav:100,300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lobster|Playfair+Display|Raleway|Roboto|Ubuntu|VT323|Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="../css/post.css" />
  <link rel="stylesheet" type="text/css" href="../css/page.css" />
  <link rel="stylesheet" type="text/css" href="../css/filesystem.css" />
  <link rel="stylesheet" type="text/css" href="../css/general.css" />
  <link rel="stylesheet" type="text/css" href="../css/window.css" />
  <link rel="stylesheet" type="text/css" href="../css/colorpicker.css" />

  <!-- JAVASCRIPT -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script src="../js/general.js"></script>
  <script src="../js/page.js"></script>
  <script src="../js/colorpicker.js"></script>
  <script src="../js/window.js"></script>
  <script src="../js/filesystem.js"></script>

  <!-- FAVICON -->
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">

  <!-- PHP -->
  <?php if (login_check($mysqli)) {
    echo "<link rel='stylesheet' type='text/css' href='../css/temp_phpstyles.php' />";
  } else {
    echo "<link rel='stylesheet' type='text/css' href='../css/phpstyles.php' />";
  }?>

  <!-- TITLE -->
  <title><?php
    if ($result !== NULL) {
      echo $result["page_title"];
    } else {
      echo "Add a post.";
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
  <form action='save_page' method='post' enctype='multipart/form-data'>
    <div class='block post_block'>
      <input type='text' placeholder='Title' class='post_title text_input' spellcheck='false' name='heading' <?php
      if ($result !== NULL) {
        echo "value='" . $result["title"] . "'";
      }
      ?> />
      <div class='post_edit_settings'>
        <div class='post_buttons'>
          <div class='left'>
          <input type='button' class='post_edit post_hide' value='&#x25b3;' title='Hide Buttons' />
          <input type='button' class='post_edit post_settings' value='&#x2699;' title='Settings' />
          <input type='button' class='post_edit post_bold' value='B' title='Bold' />
          <input type='button' class='post_edit post_underline' value='U' title='Underline' />
          <input type='button' class='post_edit post_italicize' value='I' title='Italicize' />
          <input type='button' class='post_edit post_overline' value='O' title='Line Through' />
          <input type='button' class='post_edit post_hr' value='&#x2015;' title='Break' />
          <input type='button' class='post_edit post_quote' value='&#x201c;&#x201d;' title='Quote' />
          <input type='button' class='post_edit post_image' value='&#x1F4F7;' title='Image' />
          <input type='button' class='post_edit post_file' value='&#x1f4c2;' title='File' />
          <input type='button' class='post_edit post_color' value='A' title='Text Color' />
          <input type='button' class='post_edit post_highlight' value='A' title='Highlight' />
          <button type='button' class='post_edit post_table' title='Table'>&#x25a1;&#x25a1;&#x25a1;<br />&#x25a1;&#x25a1;&#x25a1;</button>
          <button type='button' class='post_edit post_add_col' title='Add Column'>&#x25a1;<br />&#x25a1;</button>
          <input type='button' class='post_edit post_add_row' value='&#x25a1;&#x25a1;' title='Add Row' />
          <button type='button' class='post_edit post_remove_col' title='Remove Column'>&#x2385;<br />&#x2385;</button>
          <input type='button' class='post_edit post_remove_row' value='&#x23db;&#x23db;' title='Remove Row' />
          <button type='button' class='post_edit post_list' title='Unordered List'>&#x25e6;-<br />&#x25e6;-</button>
          <button type='button' class='post_edit post_olist' title='Ordered List'>1-<br />2-</button>
          <button type='button' class='post_edit post_text_left' title='Align Text to Left'>--<br />---<br />--</button>
          <button type='button' class='post_edit post_text_center' title='Center Text'>--<br />---<br />--</button>
          <button type='button' class='post_edit post_text_right' title='Align Text to Right'>--<br />---<br />--</button>
          <input type='button' class='post_edit post_link' value='&#x1f517;' title='Link' />
          <input type='button' class='post_edit post_video' value='&#x25b6;' title='Video' />
          <select class='post_edit post_edit_long post_heading'>
            <option value='first' selected disabled>Heading</option>
            <option>h1</option>
            <option>h2</option>
            <option>h3</option>
            <option>h4</option>
            <option>h5</option>
            <option>h6</option>
          </select>
          <select  class='post_edit post_edit_long post_font_size'>
            <option value='first' selected disabled>Font Size</option>
            <option>6</option>
            <option>8</option>
            <option>10</option>
            <option>12</option>
            <option>14</option>
            <option>16</option>
            <option>18</option>
            <option>20</option>
            <option>22</option>
            <option>24</option>
            <option>26</option>
            <option>28</option>
            <option>30</option>
            <option>32</option>
            <option>34</option>
            <option>36</option>
            <option>38</option>
            <option>40</option>
            <option>42</option>
            <option>44</option>
            <option>46</option>
            <option>48</option>
            <option>50</option>
            <option>52</option>
          </select>

          <?php
          include ROOT . "/site/fonts.php";
          ?>

          </div>
          <div class='right'>

          <input type='button' class='post_edit post_html_btn' value='HTML' />
          <input type='button' class='post_edit post_visual_btn selected' value='Visual' />

          </div>
        </div>
        <div class='post_textarea'>
          <input type='button' class='post_edit post_show' value='&#x25bd;' title='Show Buttons' />
          <pre class='post_html' name='post_text' contenteditable="true" spellcheck="false"></pre>
          <div class='post_visual' contenteditable="true" spellcheck="false"><?php
            if ($result != NULL) {
              echo $result["post"];
            }
          ?></div>
          <div class="lightbox">
          </div>
            <img src="../svg/close_white.svg" class='lightbox_button close_lightbox' onclick="closeImage(true);" />
            <img src="../svg/right_white.svg" class='lightbox_button lightbox_right' onclick="rightImage();" />
            <img src="../svg/left_white.svg" class='lightbox_button lightbox_left' onclick="leftImage();" />
            <img src="../svg/plus.svg" class='lightbox_button lightbox_zoomin' onclick="zoominImage();" />
            <img src="../svg/minus.svg" class='lightbox_button lightbox_zoomout' onclick="zoomoutImage();" />
            <img src="../img/background.png" class='lightbox_button lightbox_background_grid' onclick="setBackgroundImage(this);" />
            <div class='lightbox_button lightbox_background_black' onclick="setBackgroundImage(this);"></div>
            <div class='lightbox_button lightbox_background_white' onclick="setBackgroundImage(this);"></div>
        </div>
      </div>
      <div class="save_block">
        <div class="save_draft" data-public="0">Save as Draft</div>
        <div class="save_publish" data-public="1">Publish</div>
      </div>
    </div>
  </form>
  <?php
  include ROOT . "/includes/windows.php";
  include ROOT . "/site/footer.php";
  ?>
  <script src='../js/post.js'></script>
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
