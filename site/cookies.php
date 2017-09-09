<?php

include "../define.php";
include ROOT . "/includes/db_connect.php";
include ROOT . "/includes/functions.php";
include ROOT . "/site/json.php";

?>


<div class='cookie_notice'>
  <span class='cookie_short'><?php echo $json->data->essentials->cookie_short; ?></span>
  <span class='cookie_more' onclick='showCookieWindow();'><?php echo $json->data->essentials->cookie_more; ?></span>
  <span class='cookie_short_agree cookie_button' onclick='agreeCookies();'><?php echo $json->data->essentials->cookie_short_agree; ?></span>
</div>
