<?php

include "../define.php";
include_once ROOT . '/includes/functions.php';

sec_session_start();


if (login_check($mysqli) && !empty($_POST)) {
    include_once ROOT . "/edit/save.php";
    print_r($_POST);
    $save->content($_POST["parentkey"], $_POST, ROOT . "/json/temp_data.json");
    header("Location: ../");
    exit;
} else {
  header("Location: ../error?status=401");
  exit;
}
?>
