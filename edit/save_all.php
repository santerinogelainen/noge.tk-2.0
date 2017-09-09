<?php

include "../define.php";
include_once ROOT . '/includes/functions.php';

sec_session_start();

if (login_check($mysqli)) {
  include_once ROOT . '/edit/save.php';
  $save->all(ROOT . '/json/data.json', ROOT . '/json/css.json');
  header("Location: " . BASE_URL);
} else {
  header("Location: ../error?status=401");
  exit;
}
?>
