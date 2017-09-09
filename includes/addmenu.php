<?php

require_once "menu.php";

class AddMenu extends Menu {
  
  private $html = "<img class='addbutton' src='/svg/add_white.svg'/><div class='add window'><form method='post' action='edit/save_content' enctype='multipart/form-data'><img class='close close_window' src='/svg/close.svg'/>";
  
  function __construct($key, $mysqli = false, $ajax = false, $selecttarget = false) {
    $this->addTitle($key);
    if ($selecttarget) {
      $this->selectTarget();
    }
    $this->showSelections();
    $this->addHrefChoise();
    $this->chooseUrl();
    $this->chooseFile();
    $this->choosePost($mysqli);
    $this->chooseImagePost($mysqli);
    $this->chooseContentPage($mysqli);
    $this->chooseContent($key, $ajax);
  }
  
  public static function getPosts($mysqli, $table) {
    $sql = "SELECT `id`,`timestamp`,`page_title` FROM " . $mysqli->real_escape_string($table) . " WHERE `public`=1";
    if ($result = $mysqli->query($sql)) {
      $rows = [];
      while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $rows[] = $row;
      }
      return $rows;
    } else {
      return false;
    }
  }
  
  private function addTitle($string) {
    $this->html .= "<h4>Add content to " . $string . "</h4>";
  }
  
  private function selectTarget() {
    $this->html .= "<div class='window_section'><select></select></div>";
  }
  
  private function showSelections() {
    $this->html .= "<div class='add_hide' data-show='content'>";
    $this->html .= "<input class='add_back_to_start' type='button' value='Back To Start'/>";
    $this->html .= "<input class='add_href' name='href' type='text' required/>";
    $this->html .= "</div>";
  }
  
  private function addHrefChoise() {
    $this->html .= "<div class='add_choose' data-show='choose'>";
    $this->html .= "<h5>Choose href:</h5>";
    $this->html .= "<input class='add_choose_url' data-show='url' onclick='dataShow(this)' type='button' value='URL' />";
    $this->html .= "<input class='add_choose_file' data-show='file' onclick='dataShow(this)' type='button' value='Folder or File' />";
    $this->html .= "<input class='add_choose_post' data-show='post' onclick='dataShow(this)' type='button' value='Post' />";
    $this->html .= "<input class='add_choose_imagepost' data-show='imagepost' onclick='dataShow(this)' type='button' value='Image post' />";
    $this->html .= "<input class='add_choose_content' data-show='contentpage' onclick='dataShow(this)' type='button' value='Content page' />";
    $this->html .= "</div>";
  }
  
  private function chooseUrl() {
    $this->html .= "<div data-show='url' class='add_url add_hide'>";
    $this->html .= "<h5>Url:</h5>";
    $this->html .= "<input type='text'/>";
    $this->html .= "<input type='button' value='Back' onclick='dataShow(this)' data-show='choose' />";
    $this->html .= "<input type='button' value='Next' onclick='dataShow(this)' data-show='content' />";
    $this->html .= "</div>";
  }
  
  private function chooseFile() {
    $this->html .= "<div data-show='file' class='add_file add_hide'>";
    $this->html .= $this->addFileSelection("Choose file or folder:", "radio", "choose", true);
    $this->html .= "<input type='button' value='Back' onclick='dataShow(this)' data-show='choose' />";
    $this->html .= "<input type='button' value='Next' onclick='dataShow(this)' data-show='content' />";
    $this->html .= "</div>";
  }
  
  public static function createPostSelectionTable($posts, $type, $input = "radio") {
    $html = "<table class='add_post_table'><thead><tr><th></th><th>Id</th><th>Timestamp</th><th>Page title</th></tr></thead><tbody>";
    foreach ($posts as $value) {
      $html .= "<tr><td><label><input type='" . $input . "' name='posts' value='/" . $type . "?id=" . $value["id"] . "' data-title='" . $value["page_title"] . "'/></label></td>";
      foreach ($value as $col) {
        $html .= "<td>" . $col . "</td>";
      }
      $html .= "</tr>";
    }
    $html .= "</tbody></table>";
    return $html;
  }
  
  private function choosePost($mysqli) {
    $this->html .= "<div data-show='post' class='add_post add_hide'>";
    $this->html .= "<h5>Post:</h5>";
    $posts = $this->getPosts($mysqli, "posts");
    $this->html .= $this->createPostSelectionTable($posts, "post");
    $this->html .= "<input type='button' value='Back' onclick='dataShow(this)' data-show='choose' />";
    $this->html .= "<input type='button' value='Next' class='add_post_next' onclick='dataShow(this)' data-show='content' />";
    $this->html .= "</div>";
  }
  
  private function chooseImagePost($mysqli) {
    $this->html .= "<div data-show='imagepost' class='add_imagepost add_hide'>";
    $this->html .= "<h5>Image post:</h5>";
    $posts = $this->getPosts($mysqli, "imageposts");
    $this->html .= $this->createPostSelectionTable($posts, "image");
    $this->html .= "<input type='button' value='Back' onclick='dataShow(this)' data-show='choose' />";
    $this->html .= "<input type='button' value='Next' class='add_post_next' onclick='dataShow(this)' data-show='content' />";
    $this->html .= "</div>";
  }
  
  private function chooseContentPage($mysqli) {
    $this->html .= "<div data-show='contentpage' class='add_contentpage add_hide'>";
    $this->html .= "<h5>Content Page:</h5>";
    $posts = $this->getPosts($mysqli, "content");
    $this->html .= $this->createPostSelectionTable($posts, "content");
    $this->html .= "<input type='button' value='Back' onclick='dataShow(this)' data-show='choose' />";
    $this->html .= "<input type='button' value='Next' class='add_post_next' onclick='dataShow(this)' data-show='content' />";
    $this->html .= "</div>";
  }
  
  private function chooseContent($key, $ajax) {
    $this->html .= "<div data-show='content' class='add_content add_hide'>";
    $this->html .= "<h5>Title:</h5>";
    $this->html .= "<input type='text' name='title' class='add_title' required/>";
    $this->html .= "<div class='add_key'>";
    $this->html .= "<input type='text' name='key' required/>";
    $this->html .= "</div>";
    $this->html .= $this->addFileSelection("Choose image:", "radio", "image[]");
    $this->html .= "<h5>Description</h5>";
    $this->html .= "<textarea name='desc' class='add_description'></textarea>";
    $this->html .= "<input style='display: none;' name='parentkey' value='" . $key . "' />";
    if ($ajax) {
      $this->html .= "<input type='button' class='add_to_section' value='Add'/>";
    } else {
      $this->html .= "<input type='submit' value='Submit'/>";
    }
    $this->html .= "</div>";
  }
  
  public function html() {
    $this->html .= "</form>";
    $this->html .= "</div>";
    return $this->html;
  }
  
}
?>
