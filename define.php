<?php

  //root of the document, example:
  //   C:\\xampp\htdocs
  //   /var/www/html/nogesite
  define('ROOT', __DIR__);

  //uri of the document (from browser), example:
  //   /
  //   /nogesite
  $slash = str_replace("\\", "/", ROOT);
  define('URI', str_replace($_SERVER['DOCUMENT_ROOT'], "", $slash . "/"));

  include_once ROOT . "/includes/psl-config.php";

  //base url of the document, example:
  //   http://localhost/
  //   https://noge.tk/nogesite/

  //////////////////IMPORTANT///////////////////////
  /* REMEMBER TO SET SECURE TO TRUE IN PSL-CONFIG */
  /* IF YOU ARE USING HTTPS/SSL                   */

  if (SECURE) {
    define('BASE_URL', "https://" . $_SERVER['HTTP_HOST'] . URI);
  } else {
    define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . URI);
  }
?>
