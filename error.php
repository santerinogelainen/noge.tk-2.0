<?php
include "define.php";
$errorcode = $_GET["status"];
http_response_code($errorcode);

switch ($errorcode) {
  case 400:
    $error = "Bad Request";
    break;
  case 401:
    $error = "Unauthorized";
    break;
  case 403:
    $error = "Forbidden";
    break;
  case 404:
    $error = "Not Found";
    break;
  case 500:
    $error = "Internal Server Error";
    break;
  default:
    $error = "Unknown error";
    $errorcode = "???";
    break;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $error;?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>/css/error.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>/css/general.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>/css/phpstyles.php" />
    </head>
    <body>
      <?php include ROOT . "/site/page_header.php";?>
        <div id="animate"><h1><?php echo $errorcode; ?></h1>
        <div class="error">
          <?php
            echo $error;
          ?>
        </div>
        </div>
        <script src="<?php echo "https://" . $_SERVER['HTTP_HOST']; ?>/js/error.js"></script>
    </body>
</html>
