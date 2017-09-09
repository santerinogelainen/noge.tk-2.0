<?php

include "../define.php";

$get = $_GET["data"];

if ($get == "names") {
  
  function getNames($string) {
    return str_replace(array(ROOT . "/socialmedia/", ".php"), array("", ""), $string);
  }
  
  $paths = array_values( preg_grep( '/^((?!index.php).)*$/', glob(ROOT . "/socialmedia/*.php")));
  $files = array_map("getNames", $paths);
  
  echo json_encode($files);
} else {
  include ROOT . '/includes/getapikeys.php';
  include ROOT . '/site/json.php';
  if (file_exists(ROOT . "/socialmedia/" . $get . ".php")) {
    include ROOT . "/socialmedia/" . $get . ".php";
  }
}


?>
