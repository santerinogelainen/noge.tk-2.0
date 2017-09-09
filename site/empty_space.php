<?php

  function empty_space($attr = '', $front = false) {

    //variables
    global $json; //from: json.php
    global $mysqli;

    //if json attribute not declared
    if ($attr == '') {
      echo "Error, didn't specify json attribute!";
      return false;
    }

    //if json attribute does not exist
    if (!property_exists($json->data, $attr)) {
      echo "Error, json attribute does not exist!";
      return false;
    }

    echo "<div class='block " . $attr . "' style='background-image: url(&quot;" . $json->data->$attr->image[0] . "&quot;);'>";

    //if logged in add edit possibilities
    if (login_check($mysqli) == true) {
      $menu = new EditMenu(array(
          "data" => $json->data,
          "key" => $attr,
          "title" => "title",
          "image" => array(1, 75),
          "colorpicker" => array("color", "title_background"),
          "fixed" => "bg_fixed",
          "position" => "title_position"
      ));
      echo $menu->html();
    }

    if ($front) {
      echo "<img class='scroll_initiative' onclick='toastMsg(&quot;SCROLL DOWN&quot;)' src='" . $json->css->{'.scroll_initiative'}->src . "'/>";
    }

    //empty space title if turned on in edit
    if ($json->data->$attr->title != "") {
      echo "<h1 style='color: hsla(" . $json->data->$attr->color[0] . ", " . $json->data->$attr->color[1] . "%, " . $json->data->$attr->color[2] . "%, " . $json->data->$attr->color[3] . "); background-color: hsla(" . $json->data->$attr->title_background[0] . ", " . $json->data->$attr->title_background[1] . "%, " . $json->data->$attr->title_background[2] . "%, " . $json->data->$attr->title_background[3] . ");'>" . $json->data->$attr->title . "</h1>";
    }
    echo "</div>";
  }
?>
