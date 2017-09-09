<?php

  require_once "imageprocessor.php";



  class FileSystem {
    
    private $root_folder;
    private $uri_folder;
    private $file_iterator;
    private $files = array();
    private $thumbnail_size;
    public $current_folder;
    
    
    function __construct($folder, $thumbnail = 200) {
      $this->root_folder = dirname(dirname(__FILE__));
      $this->uri_folder = $folder;
      $this->file_iterator = new DirectoryIterator($this->root_folder . $folder);
      $this->thumbnail_size = $thumbnail;
      $this->current_folder = $this->file_iterator->getPath();
      $this->getFileInfos();
    }
    
    
    private function getParentFolder() {
      if ($this->current_folder == $this->root_folder) {
        return "";
      } else {
        return dirname($this->current_folder);
      }
    }
    
    
    //checks the mime-type of the file and returns a url to an icon that represents that mime-type
    private function getFileThumbnail($filepath) {
      $exploded = explode(".", $filepath);
      $fileend = end($exploded);
      switch (strtolower($fileend)) {
        case "png":
        case "jpeg":
        case "jpg":
        case "gif":
          return $this->createBase64Thumbnail($filepath);
          break;
        case "svg":
          return "https://" . $_SERVER["HTTP_HOST"] . str_replace($this->root_folder, "", $filepath);
          break;
        case "css":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_css.svg";
          break;
        case "html":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_html.svg";
          break;
        case "php":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_php.svg";
          break;
        case "js":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_js.svg";
          break;
        case "json":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_json.svg";
          break;
        case "xml":
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file_xml.svg";
          break;
        default:
          return "https://" . $_SERVER["HTTP_HOST"] . "/svg/file.svg";
      }
    }
    
    
    //fetches an array of file information from the file iterator to $this->files
    private function getFileInfos() {
      foreach($this->file_iterator as $file) { //loop through the files
        
        $path = str_replace($this->root_folder, "", $file->getPathname());
        $filename = $file->getFilename();
        $isdir = $file->isDir();
        
        if ($isdir) {
          $thumbnail = "https://" . $_SERVER["HTTP_HOST"] . "/svg/dir.svg";
        } else {
          $thumbnail = $this->getFileThumbnail($file->getPathname());
        }
        
        //do not want . folder (and .. folder if we are at the html root folder)
        if ($filename != "." || ($filename != ".." && $path == "/")) {
          //on .. the path is the previous folder
          if ($filename == "..") {
            $path = dirname(dirname($path));
            $filename = "&#x21A9;";
          } 
          $fileinfo = array(
            "name" => $filename,
            "path" => $path,
            "thumbnail" => $thumbnail,
            "isdir" => $isdir
          );
          
          if ($isdir) {
            array_unshift($this->files, $fileinfo);
          } else {
            array_push($this->files, $fileinfo);
          }
        }
      }
      
      $this->moveToTop("&#x21A9;");
      if ($this->current_folder == $this->root_folder) {
        unset($this->files[0]);
      }
    }
    
    
    private function moveToTop($name) {
      foreach ($this->files as $index => $value) {
        if ($value["name"] == $name) {
          unset($this->files[$index]);
          array_unshift($this->files, $value);
        }
      }
    }
    
    
    private function createBase64Thumbnail($path) {
      try {
        $image = new ImageProcessor($path);
        $image->resizeImage($this->thumbnail_size);
        return $image->getBase64Image();
      } catch (Exception $e) {
        echo $e;
        exit;
      }
    }
    
    
    public function createFolder($name) {
      $fullpath = $this->current_folder . "/" . $name;
      if (!file_exists($fullpath)) {
        mkdir($fullpath, 0755);
      }
    }
    
    
    //returns html for only directories mode
    public function htmlDirectory($shownames, $name) {
      $html = "<div class='file_system_folder' data-thumbnail='" . $this->thumbnail_size . "' data-name='$name' data-type='directory' data-folder='" . $this->uri_folder . "'>";
      foreach($this->files as $file) {
        if ($file["isdir"]) {
          $html .= "<label data-name='" . $name . "' data-type='directory' data-path='" . $file["path"] . "' title='" . $file["name"] . "' class='file_system_file file_system_dir'>";
          if ($file["name"] != "&#x21A9;") {
            $html .= "<input type='radio' name='" . $name . "' value='" . $file["path"] . "' class='file_system_hidden'>";
          }
          $html .= "<div class='file_system_border'><img src='" . $file["thumbnail"] . "' class='file_system_thumbnail' />";
          if ($shownames) {
            $html .= "<span class='file_system_name'>" . $file["name"] . "</span>";
          }
          $html .= "</div></label>";
        }
      }
      $html .= "</div>";
      return $html;
    }
    
    
    //returns html for checkbox mode
    public function htmlCheckbox($shownames, $name) {
      $html = "<div class='file_system_folder' data-thumbnail='" . $this->thumbnail_size . "' data-name='$name' data-type='checkbox' data-folder='" . $this->uri_folder . "'>";
      foreach($this->files as $file) {
        if (!$file["isdir"]) {
          $html .= "<label title='" . $file["name"] . "' class='file_system_file'>";
          $html .= "<input type='checkbox' name='" . $name . "' value='" . $file["path"] . "' class='file_system_hidden'>";
        } else {
          $html .= "<label data-name='" . $name . "' data-type='checkbox' data-path='" . $file["path"] . "' title='" . $file["name"] . "' class='file_system_file file_system_dir'>";
        }
        $html .= "<div class='file_system_border'><img src='" . $file["thumbnail"] . "' class='file_system_thumbnail' />";
        if ($shownames) {
          $html .= "<span class='file_system_name'>" . $file["name"] . "</span>";
        }
        $html .= "</div></label>";
      }
      $html .= "</div>";
      return $html;
    }
    
    //returns html for radio mode
    public function htmlRadio($shownames, $name, $choosedir = false) {
      $html = "<div class='file_system_folder' data-thumbnail='" . $this->thumbnail_size . "' data-name='$name' data-dirs='$choosedir' data-type='radio' data-folder='" . $this->uri_folder . "'>";
      foreach($this->files as $file) {
        if (!$file["isdir"]) {
          $html .= "<label title='" . $file["name"] . "' class='file_system_file'>";
          $html .= "<input type='radio' name='" . $name . "' value='" . $file["path"] . "' class='file_system_hidden'>";
        } else {
          $html .= "<label data-name='" . $name . "' data-dirs='$choosedir' data-type='radio' data-path='" . $file["path"] . "' title='" . $file["name"] . "' class='file_system_file file_system_dir'>";
          if ($choosedir && $file["name"] != "&#x21A9;") {
            $html .= "<input type='radio' name='" . $name . "' value='" . $file["path"] . "' class='file_system_hidden'>";
          }
        }
        $html .= "<div class='file_system_border'><img src='" . $file["thumbnail"] . "' class='file_system_thumbnail' />";
        if ($shownames) {
          $html .= "<span class='file_system_name'>" . $file["name"] . "</span>";
        }
        $html .= "</div></label>";
      }
      $html .= "</div>";
      return $html;
    }
    
  }


?>
