<?php

//imagepost and content page
//on add/upload get a base64 thumbnail image

include "../define.php";

include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli) && !empty($_POST)) {
  
  include_once ROOT . '/includes/imageprocessor.php';
  
  $image = new ImageProcessor(ROOT . $_POST["path"]);
  $image->resizeImage(intval($_POST["size"]));
  echo $image->getBase64Image();
  
} else {
  header("Location: ../error?status=401");
  exit;
}

?>