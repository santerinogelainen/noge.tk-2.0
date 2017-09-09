<?php


class ColorPicker {
  
  private $hsla = array(360, 100, 0, 1);
  private $html = "";
  private $title = "Color";
  private $name = "color";
  
  function __construct($settings) {
    if (isset($settings["hsla"])) {
      $this->hsla = $settings["hsla"];
    }
    if (isset($settings["title"])) {
      $this->title = $settings["title"];
    }
    if (isset($settings["name"])) {
      $this->name = $settings["name"];
    }
  }
  
  
  public function hsla() {
    $this->html .= "<h5>" . $this->title . "</h5>";
    $this->html .= "<div class='colorpicker'>";
    $this->html .= "<div class='colorpicker_curcolor' style='background-color: hsla(" . $this->hsla[0] . ", " . $this->hsla[1] . "%, " . $this->hsla[2] . "%, " . $this->hsla[3] . ");'></div>";
    $order = array(
      array(
        "name" => "hue",
        "max" => "360",
        "step" => "1"
      ), array(
        "name" => "sat",
        "max" => "100",
        "step" => "1"
      ), array(
        "name" => "light",
        "max" => "100",
        "step" => "1"
      ), array(
        "name" => "alpha",
        "max" => "1",
        "step" => "0.05"
      ));
    $i = 0;
    foreach ($this->hsla as $index => $color) {
      $this->html .= "<input class='colorpicker_color colorpicker_" . $order[$index]["name"] . " " . $order[$index]["name"] . "' type='number' name='color[" . $this->name . "][" . $i . "]' oninput='updateColorpicker(this);' min='0' max='" . $order[$index]["max"] . "' step='" . $order[$index]["step"] . "'  value='" . $color . "' />";
      $this->html .= "<div class='colorpicker_slidertrack colorpicker_slidertrack_" . $order[$index]["name"] . "'></div>";
      $this->html .= "<input class='colorpicker_slider colorpicker_slider_" . $order[$index]["name"] . " " . $order[$index]["name"] . "' type='range' name='color[" . $this->name . "][" . $i . "]' oninput='updateColorpicker(this);' min='0' max='" . $order[$index]["max"] . "' step='" . $order[$index]["step"] . "' value='" . $color . "' />";
      $i++;
    }
    $this->html .= "</div>";
    return $this->html;
  }
  
  
}
?>
