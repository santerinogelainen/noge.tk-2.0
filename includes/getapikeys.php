<?php

include_once "../define.php";
include ROOT . '/includes/functions.php';
if ($query = $mysqli->prepare("SELECT media.name,  `key`.`key` FROM media INNER JOIN  `key` ON media.id =  `key`.media")) {
  //execute query
  $query->execute();
  //get results from query
  $result = $query->get_result();

  //foreach result push to $keys array the result
  $keys = array();
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $name = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($row["name"]));
    if (!isset($keys[$name])) {
      $keys[$name] = array();
    }
    $keys[$name][] = $row["key"];
  }
  
  //close the query
  $query->close();
}


?>