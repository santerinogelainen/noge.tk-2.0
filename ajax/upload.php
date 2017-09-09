<?php

//filesystem
//upload files

include "../define.php";

include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli) && !empty($_POST) && !empty($_FILES)) {
  
  $path = ROOT . $_POST["folder"];
  
  if (!file_exists($path)) {
    mkdir($path, 0775, true);
  }
  
  foreach ($_FILES as $index => $file) {
    
    $name = preg_replace("/[^A-Za-z0-9_\-.]/","",$file["name"]);
    $filepath = $path . "/" . $name;
  
    if (!move_uploaded_file($file["tmp_name"], $filepath)) {
      echo "Error uploading file: " . $file["name"] . ".";
      exit;
    }
  }
} else {
  header("Location: ../error?status=401");
  exit;
}

?>
