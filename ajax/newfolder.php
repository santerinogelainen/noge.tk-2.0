<?php

//filesystem
//create new folder

include "../define.php";
include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli) && !empty($_POST)) {
  include_once ROOT . '/includes/filesystem.php';
  
  $filesystem = new FileSystem($_POST["folder"]);
  $filesystem->createFolder($_POST["name"]);
  
  switch ($_POST['type']) {
        case "radio":
            echo $filesystem->htmlRadio(true, $_POST["html_name"], $_POST["dirs"]);
            break;
        case "checkbox":
            echo $filesystem->htmlCheckbox(true, $_POST["html_name"]);
            break;
        case "directory":
            echo $filesystem->htmlDirectory(true, $_POST["html_name"]);
            break;
        default:
            header('Location: ../error?status=400');
            exit;
    }
  
} else {
  header("Location: ../error?status=401");
  exit;
}

?>