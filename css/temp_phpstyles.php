<?php header("Content-type: text/css; charset: UTF-8");

include "../define.php";
include ROOT . "/includes/functions.php";
include ROOT . "/site/json.php";
$json = new Json(ROOT . '/json/temp_data.json', ROOT . '/json/temp_css.json');
include ROOT . "/css/styles.php";
?>
header {
    height: 60px;
}
