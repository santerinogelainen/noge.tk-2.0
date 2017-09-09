<?php


require_once "filesystem.php";
require_once "colorpicker.php";

class Menu {
    
    private $key;
    
    
    public function addFileSelection($title, $type, $name, $dirtoradio = false) {
        $html = "<h5>$title</h5>";
        if (is_array($type)) {
          $filesystem = new FileSystem("/", $type[1]);
          $type = $type[0];
        } else {
          $filesystem = new FileSystem("/");
        }
        $html .= "<div class='file_selection'><div class='file_system_buttons'><img title='New folder' class='fs_new_folder' src='/svg/add_black.svg'><img title='Reload current folder' class='fs_reload_folder' src='/svg/reload_black.svg'><label><img title='Upload' class='fs_upload_button' src='/svg/upload_black.svg'><input class='upload_input' type='file' multiple/></label></div><div class='file_system_loader'></div>";
        switch ($type) {
          case 'checkbox':
            $html .= $filesystem->htmlCheckbox(true, $name);
            break;
          case 'radio':
            $html .= $filesystem->htmlRadio(true, $name, $dirtoradio);
            break;
          case 'directory':
            $html .= $filesystem->htmlDirectory(true, $name);
            break;
          default:
            throw new Exception("Error. Unknows file selection type");
            break;
        }
        $html .= "</div>";
        return $html;
  }
  
  
  public function getColorpickerHtml($title, $name, $hsla) {
    $colorpicker = new ColorPicker(array(
        "title" => $title,
        "name" => $name,
        "hsla" => $hsla
      ));
    return $colorpicker->hsla();
  }
  
  
}

?>