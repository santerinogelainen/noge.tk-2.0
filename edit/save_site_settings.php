<?php

include "../define.php";

include_once ROOT . '/includes/db_connect.php';
include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli) && !empty($_POST)) {
  include_once ROOT . "/edit/save.php";
  $save->css($_POST["css"], ROOT . "/json/temp_css.json");
  $save->data("essentials", $_POST["data"], ROOT . "/json/temp_data.json");
  header("Location: " . BASE_URL);
} else {
  header("Location: ../error?status=401");
  exit;
}

?>
