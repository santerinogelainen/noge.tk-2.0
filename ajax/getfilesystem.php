<?php

//filesystem
//on folder double click get the folder content and create new html for it


require_once "../define.php";
require_once ROOT . '/includes/functions.php';
require_once ROOT . "/includes/filesystem.php";

sec_session_start();

if (login_check($mysqli) && isset($_POST["path"]) && isset($_POST['type']) && isset($_POST['name'])) {
    $filesystem = new FileSystem($_POST['path'], $_POST["thumbnail"]);
    switch ($_POST['type']) {
        case "radio":
            echo $filesystem->htmlRadio(true, $_POST["name"], $_POST["dirs"]);
            break;
        case "checkbox":
            echo $filesystem->htmlCheckbox(true, $_POST["name"]);
            break;
        case "directory":
            echo $filesystem->htmlDirectory(true, $_POST["name"]);
            break;
        default:
            header('Location: ../error?status=400');
            exit;
    }
} else {
    header('Location: ../error?status=401');
    exit;
}


?>