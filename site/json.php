<?php

class Json {
  
  public $data;
  public $css;
  
  function __construct($dataurl, $cssurl) {
    $this->setData($dataurl);
    $this->setCss($cssurl);
  }
  
  //void
  public function setData($url) {
    $file = file_get_contents($url);
    $json = json_decode($file, false);
    $this->validateJson($json);
    $this->data = $json;
  }
  
  //void
  public function setCss($url) {
    $file = file_get_contents($url);
    $json = json_decode($file, false);
    $this->validateJson($json);
    $this->css = $json;
  }
  
  public function fromURL($url, $keys = array(), $decode = true, $assoc = false, $time = 10) {
    $timeout = stream_context_create(array(
      'http' => array(
        'timeout' => $time,
        'header' => array(
          'User-Agent: PHP'
        )
      )
    ));
    if (!empty($keys)) {
      $url .= "?";
      $keyAmount = count($keys);
      $index = 1;
      foreach($keys as $key => $value) {
        $url .= $key . "=" . $value;
        if ($keyAmount != $index) {
          $url .= "&";
        }
        $index++;
      }
    }
    
    if ($result = @file_get_contents($url, false, $timeout)) {
      if ($decode) {
        $decoded = json_decode($result, $assoc);
        return $decoded;
      } else {
        return $result;
      }
    } else {
      return false;
    }
  }
  
  
  //void
  private function validateJson($json) {
    if (is_null($json)) {
      throw new Exception("Json is not valid. Try validating it online.");
    }
  }
  
  //void
  public function saveAll($datatarget, $csstarget) {
    file_put_contents($datatarget, json_encode($this->data));
    file_put_contents($csstarget, json_encode($this->css));
  }
  
}


//editable elements
if (login_check($mysqli)) {
  $json = new Json(ROOT . '/json/temp_data.json', ROOT . '/json/temp_css.json');
} else {
  $json = new Json(ROOT . '/json/data.json', ROOT . '/json/css.json');
}

?>
