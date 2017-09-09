<?php

require_once "../define.php";
require_once ROOT . '/site/json.php';

class Save extends Json {
  
  private $curdata;
  
  //void
  public function all($datatarget, $csstarget) {
    file_put_contents($datatarget, json_encode($this->data));
    file_put_contents($csstarget, json_encode($this->css));
  }
  
  
  public function content($key, $data, $target) {
    $this->setCurdata($key);
    $content = new StdClass();
    $content->href = $data["href"];
    $content->title = $data["title"];
    $content->image = $data["image"];
    $content->desc = $data["desc"];
    $this->curdata->content->{$data["key"]} = $content;
    $this->curdata->order[] = $data["key"];
    file_put_contents($target, json_encode($this->data));
  }
  
  //void
  public function data($key, $data, $target) {
    
    //pointer to the original json data
    $this->setCurdata($key);
    
    if ($key == "essentials") {
      $this->change($data);
    } else {
      if (isset($data["title"])) { //
        $this->change($data["title"]);
      }
      if (isset($data["text"])) {
        $this->change($data["text"]);
      }
      if (isset($data["sm"])) {
        $this->toggleSM($data["sm"]);
      }
      if (isset($data["table"])) {
        $this->change($data["table"]);
      }
      if (isset($data["image"])) {
        $this->changeSingleValue("image", $data["image"]);
      }
      if (isset($data["order"])) {
        $this->changeSingleValue("order", $data["order"]);
      }
      if (isset($data["remove"])) {
        $this->remove($data["remove"]);
      }
      if (isset($data["color"])) {
        $this->change($data["color"]);
      }
      if (isset($data["position"])) {
        $this->change($data["position"]);
      }
      if (isset($data["fixed"])) {
        $this->change($data["fixed"]);
      }
    }
    
    //save json to target file
    file_put_contents($target, json_encode($this->data));
    print_r($this->data);
  }
  
  
  //void
  public function css($css, $target) {
    file_put_contents($target, json_encode($css));
  }
  
  
  //void
  private function setCurdata($key) {
    $key = html_entity_decode($key);
    if (strpos($key, "->") !== false) {
      $keys = explode("->", $key);
      foreach ($keys as $index => $key) {
        if ($index == 0) {
          $this->curdata = &$this->data->$key;
        } else {
          $this->curdata = &$this->curdata->$key;
        }
      }
    } else {
      $this->curdata = &$this->data->$key;
    }
  }
  
  
  //void
  //used for most changes
  private function change($data) {
    foreach ($data as $key => $value) {
      $this->curdata->$key = $value;
    }
  }
  
  //void
  private function toggleSM($medias) {
    foreach ($medias as $filename) {
      $exploded = explode(".", $filename);
      $fileend = array_pop($exploded);
      if ($fileend == "old") {
        rename("../socialmedia/" . $filename, "../socialmedia/" . implode(".", $exploded));
      } else {
        rename("../socialmedia/" . $filename, "../socialmedia/" . $filename . ".old");
      }
    }
  }
  
  //void
  private function changeSingleValue($key, $data) {
    $this->curdata->$key = $data;
  }
  
  //void
  private function remove($data) {
    foreach ($data as $key) {
      unset($this->curdata->content->$key);
      $foundkey = array_search($key, $this->curdata->order);
      if ($foundkey !== false) {
        unset($this->curdata->order[$foundkey]);
      }
    }
  }
  
}

$save = new Save(ROOT . '/json/temp_data.json', ROOT . '/json/temp_css.json');

?>
