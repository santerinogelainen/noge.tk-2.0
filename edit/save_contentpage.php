<?php
include "../define.php";

include ROOT . '/includes/db_connect.php';
include ROOT . '/includes/functions.php';

sec_session_start();

if (!empty($_POST) && login_check($mysqli)) {
  
  //set variable
  $id = intval($_POST["id"]);
  $post = $_POST["post"];
  $public = intval($_POST["public"]);
  $page_title = $_POST["custom_title"];
  
  //check if post with this id exists
  if ($query = $mysqli->prepare("SELECT id FROM content WHERE id=?")) {
    $query->bind_param("i", $id);
    if ($query->execute()) {
      $result = $query->get_result();
      if ($result->num_rows > 0) {
        $exists = true;
      } else {
        $exists = false;
      }
    } else {
      echo "Mysql execute error.";
      exit;
    }
    $query->close();
  } else {
    echo "Mysql prepare error.";
    exit;
  }
  
  //different querys if the post exists or not
  if ($exists) {
    $sql = "UPDATE content SET `page_title`=?, post=?, public=? WHERE id=?";
  } else {
    $sql = "INSERT INTO content (`page_title`, post, public, id) VALUES (?, ?, ?, ?)";
  }
  
  //insert/update
  if ($query = $mysqli->prepare($sql)) {
    $query->bind_param("ssii", $page_title, $post, $public, $id);
    if ($query->execute()) {
      if ($public == 0) {
        echo "Saved as a draft.";
      } else {
        echo "Saved and published. <a target='_blank' href='" . BASE_URL . "content?id=" . $id . "'>Preview</a>";
      }
    } else {
      echo "Mysql execute error.";
      exit;
    }
    $query->close();
  } else {
    echo "Mysql prepare error.";
    exit;
  }
} else {
  header("Location: ../error?status=403");
  exit();
}

?>
