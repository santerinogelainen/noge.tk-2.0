<?php

include "../define.php";
include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli)) {
  include_once ROOT . '/edit/save.php';
  $save = new Save(ROOT . '/json/data.json', ROOT . '/json/css.json');
  $save->all(ROOT . '/json/temp_data.json', ROOT . '/json/temp_css.json');
  header("Location: " . BASE_URL);
} else {
  header("Location: ../error?status=401");
  exit;
}
?>
