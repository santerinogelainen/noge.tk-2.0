
<?php

require_once "filesystem.php";
require_once "includes/functions.php";
require_once "site/json.php";
require_once "finder.php";
require_once "menu.php";




class EditMenu extends Menu {
  
  private $data;
  private $name;
  private $html = "<img class='editbutton' src='svg/edit.svg' /><div class='edit window'><form method='post' action='edit/save_changes' enctype='multipart/form-data'><img class='close_window' src='/svg/close.svg'/>";
  
  //settings is an array of settings
  function __construct($settings) {
    if (!isset($settings["data"])) {
      throw new Exception("Data not set in settings array.");
    } if (!isset($settings["key"])) {
      throw new Exception("Key not set in settings array.");
    }
    
    if (isset($settings["name"])) {
      $this->name = $settings["name"];
    } else {
      $this->name = $settings["key"];
    }
    $this->data = $settings["data"];
	  $this->data = $this->data->{$settings["key"]};
    $this->html .= "<h4><span>[" . $settings["key"] . "]</span></h4>";
    $this->checkSettings($settings);
    
  }
  
  private function checkSettings($settings) {
    if (isset($settings["title"])) {
      $this->addTitle($settings["title"]);
    }
    if (isset($settings["text"])) {
      $this->addTextarea($settings["text"]);
    }
    if (isset($settings["social_media"])) {
      $this->addSocialMediaToggle();
    }
    if (isset($settings["table"])) {
      $this->addTable($settings["table"]);
    }
    if (isset($settings["image"])) {
      if ($settings["image"][0] === 1) {
        $settings["image"][0] = "radio";
      } else {
        $settings["image"][0] = "checkbox";
      }
      $this->html .= $this->addFileSelection("Image: ", $settings["image"], "image[]");
    }
    if (isset($settings["remove"])) {
      $this->addRemove($settings["remove"]);
    }
    if (isset($settings["order"])) {
      $this->addOrder($settings["order"]);
    }
    if (isset($settings["colorpicker"])) {
      $this->addColorpicker($settings["colorpicker"]);
    }
    if (isset($settings["position"])) {
      $this->addPosition($settings["position"]);
    }
    if (isset($settings["fixed"])) {
      $this->addFixedBackground($settings["fixed"]);
    }
  }
  
  
  private function addTitle($key) {
    $this->html .= "<h5>Title: </h5>";
    $this->html .= "<input type='text' class='editmenu_title' name='title[" . $key . "]' value='" . $this->data->$key . "' />";
  }
  
  
  private function addTextarea($keys) {
    $this->html .= "<div class='editmenu_texts'>";
    foreach ($keys as $key) {
      $this->html .= "<div class='editmenu_text'>";
      $this->html .= "<h5>" . $key . "</h5>";
      $this->html .= "<textarea name='text[" . $key . "]' class='editmenu_textarea'/>" . $this->data->$key . "</textarea>";
      $this->html .= "</div>";
    }
    $this->html .= "</div>";
  }
  
  
  //TO DO: REWRITE JAVASCRIPT THAT DEALS WITH THESE TABLES
  private function addTable($keys) {
    foreach ($keys as $key) {
      $table = $this->data->$key;
      $this->html .= "<h5>" . $key . "</h5>";
      $this->html .= "<table class='editmenu_table'><tbody>";
      $this->html .= "<tr class='editmenu_tr' data-row='0'>";
      foreach ($table->{1} as $index => $data) {
        $this->html .= "<td class='remove_col editmenu_table_remove' data-col='" . $index . "' onclick='disableCells(this);'>(Disable)</td>";
      }
      $this->html .= "<td class='add_cells' data-name='table[" . $key . "]'><img onclick='addRow(this);' class='add_row' title='Add Row' src='/svg/row.svg' /><img onclick='addCol(this);' class='add_col' title='Add Column' src='/svg/col.svg' /></td></tr>";
      foreach ($table as $rowindex => $row) {
        $this->html .= "<tr data-row='" . $rowindex . "'>";
        foreach ($row as $colindex => $col) {
          $this->html .= "<td data-col='" . $colindex . "' data-row='" . $rowindex . "'><textarea name='table[" . $key . "][" . $rowindex . "][" . $colindex . "]'>" . $col . "</textarea></td>";
        }
        $this->html .= "<td class='remove_row editmenu_table_remove' data-row='" . $rowindex . "' onclick='disableCells(this);'>(Disable)</td></tr>";
      }
      $this->html .= "</tbody></table>";
    }
  }
  
  
  private function addSocialMediaToggle() {
    $this->html .= "<h5>Toggle Social Media visibility</h5>";
    $this->html .= "<div class='editmenu_notice'>These changes are applied to the production version</div>";
    $dir = new DirectoryIterator("socialmedia");
    foreach ($dir as $file) {
	  $filename = $file->getFilename();
      if (!$file->isDir() && $filename != "index.php") {
		    $exploded = explode(".", $filename);
        if (end($exploded) == "old") {
          $this->html .= "<label class='editmenu_toggle_sm'><input name='sm[]' type='checkbox' value='" . $file->getFilename() . "' /><span style='color: red;'>" . $file->getFilename() . "</span></label>";
        } else {
          $this->html .= "<label class='editmenu_toggle_sm'><input name='sm[]' type='checkbox' value='" . $file->getFilename() . "' /><span style='color: green;'>" . $file->getFilename() . "</span></label>";
        }
      }
    }
  }
  
  
  private function addRemove($key) {
    $this->html .= "<h5>Remove: </h5>";
    foreach ($this->data->$key as $content_key => $content) {
      $this->html .= "<label class='editmenu_remove'><input type='checkbox' name='remove[]' value='" . $content_key . "' /><span>" . $content->title . "</span></label>";
    }
  }
  
  
  private function addOrder($key) {
    $this->html .= "<h5>Order: </h5><ol class='editmenu_order'>";
    foreach ($this->data->$key as $index => $key) {
      $this->html .= "<li class='editmenu_order_item'><span>(" . ($index + 1) . ")</span><span> " . $key . "</span><input type='text' name='order[]' value='" . $key . "'></li>";
    }
    $this->html .= "</ol>";
  }
  
  
  private function addColorpicker($keys) {
    foreach ($keys as $key) {
      if (is_array($key)) {
        $finder = new Finder($key, $this->data);
        $result = $finder->getResults();
        foreach ($result["value"] as $title => $color) {
          $name = str_replace("{KEY}", $title, $result["title"]);
          $this->html .= $this->getColorpickerHtml($name, $name, $color);
        }
      } else {
        $this->html .= $this->getColorpickerHtml($key, $key, $this->data->$key);
      }
    }
  }
  
  
  private function addPosition($key) {
    $this->html .= "<h4>Title position</h4>";
    if ($this->data->$key == "left") {
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='left' checked='checked' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='center' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='right' />";
    } else if ($this->data->$key == "center") {
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='left' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='center' checked='checked' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='right' />";
    } else if ($this->data->$key == "right") {
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='left' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='center' />";
      $this->html .= "<input type='radio' class='editmenu_title_position_input' name='position[" . $key . "]' value='right' checked='checked' />";
    }
  }
  
  
  private function addFixedBackground($key) {
    $this->html .= "<h4>Fixed background: ";
    if (intval($this->data->$key) == 1) {
      $this->html .= "Yes: <input type='radio' class='editmenu_fixed_input' name='fixed[" . $key . "]' value='1' checked='checked' />";
      $this->html .= "No: <input type='radio' class='editmenu_fixed_input' name='fixed[" . $key . "]' value='0'/>";
    } else {
      $this->html .= "Yes: <input type='radio' class='editmenu_fixed_input' name='fixed[" . $key . "]' value='1' />";
      $this->html .= "No: <input type='radio' class='editmenu_fixed_input' name='fixed[" . $key . "]' value='0' checked='checked' />";
    }
    $this->html .= "</h4>";
  }
  
  
  public function html() {
    return $this->html . "<input type='text' name='name' style='display: none;' value='" . $this->name . "' /><input class='save_to_temp' type='submit' value='Save to Temp' /></form></div>";
  }
  
}

?>