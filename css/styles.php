<?php
$order = $json->data->social_media->order;

foreach ($json->css as $selector => $styles) {
  echo $selector . " {";
    foreach ($styles as $style => $value) {
      if ($style != "src") {
        echo $style . ": " . $value . ";";
      }
    }
  echo "}";
}

function flexOrder($obj, $prefix = "") {
  $i = 1;
  foreach ($obj as $val) {
    echo "#" . $prefix . $val . ".content_block {
      order: " . $i . ";
    }";
    $i++;
  }
}

flexOrder($order);
$order = $json->data->photography_art->order;
flexOrder($order, "pa_");
$order = $json->data->work->order;
flexOrder($order, "wo_");


if (!empty($json->data->social_media->content_pic)):
?>
.sm_content .content_pic {
  filter: <?php echo $json->data->social_media->content_pic; ?>;
}
<?php
endif;
if (!empty($json->data->photography_art->content_pic)):
?>
.pa_content .content_pic {
  filter: <?php echo $json->data->photography_art->content_pic; ?>;
}
<?php
endif;
if (!empty($json->data->work->content_pic)):
?>
.wo_content .content_pic {
  filter: <?php echo $json->data->work->content_pic; ?>;
}
<?php
endif;
if ($json->data->front_page->bg_fixed == 1):
?>
.front_page {
  background-attachment: fixed;
}
<?php
else:
?>
.front_page {
  background-attachment: scroll;
}
<?php
endif;
if ($json->data->work_empty_space->bg_fixed == 1):
?>
.work_empty_space {
  background-attachment: fixed;
}
<?php
else:
?>
.work_empty_space {
  background-attachment: scroll;
}
<?php
endif;
if ($json->data->photography_art_empty_space->bg_fixed == 1):
?>
.photography_art_empty_space {
  background-attachment: fixed;
}
<?php
else:
?>
.photography_art_empty_space {
  background-attachment: scroll;
}
<?php
endif;
if ($json->data->social_media_empty_space->bg_fixed == 1):
?>
.social_media_empty_space {
  background-attachment: fixed;
}
<?php
else:
?>
.social_media_empty_space {
  background-attachment: scroll;
}
<?php
endif;
if (strpos($_SERVER["HTTP_USER_AGENT"], "iPad") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "iPhone") !== false  || strpos($_SERVER["HTTP_USER_AGENT"], "Android") !== false):
?>
.block {
  background-attachment: scroll !important;
}
<?php
endif;
if ($json->data->social_media_empty_space->title_position == "left"):
?>
.block.social_media_empty_space {
  justify-content: flex-start;
}
.block.social_media_empty_space h1 {
  margin-left: 15%;
}
<?php
endif;
if ($json->data->social_media_empty_space->title_position == "center"):
?>
.block.social_media_empty_space {
  justify-content: center;
}
<?php
endif;
if ($json->data->social_media_empty_space->title_position == "right"):
?>
.block.social_media_empty_space {
  justify-content: flex-end;
}
.block.social_media_empty_space h1 {
  margin-right: 15%;
}
<?php
endif;
if ($json->data->front_page->title_position == "left"):
?>
.block.front_page {
  justify-content: flex-start;
}
.block.front_page h1 {
  margin-left: 15%;
}
<?php
endif;
if ($json->data->front_page->title_position == "center"):
?>
.block.front_page {
  justify-content: center;
}
<?php
endif;
if ($json->data->front_page->title_position == "right"):
?>
.block.front_page {
  justify-content: flex-end;
}
.block.front_page h1 {
  margin-right: 15%;
}
<?php
endif;
if ($json->data->work_empty_space->title_position == "left"):
?>
.block.work_empty_space {
  justify-content: flex-start;
}
.block.work_empty_space h1 {
  margin-left: 15%;
}
<?php
endif;
if ($json->data->work_empty_space->title_position == "center"):
?>
.block.work_empty_space {
  justify-content: center;
}
<?php
endif;
if ($json->data->work_empty_space->title_position == "right"):
?>
.block.work_empty_space {
  justify-content: flex-end;
}
.block.work_empty_space h1 {
  margin-right: 15%;
}
<?php
endif;
if ($json->data->photography_art_empty_space->title_position == "left"):
?>
.block.photography_art_empty_space {
  justify-content: flex-start;
}
.block.photography_art_empty_space h1 {
  margin-left: 15%;
}
<?php
endif;
if ($json->data->photography_art_empty_space->title_position == "center"):
?>
.block.photography_art_empty_space {
  justify-content: center;
}
<?php
endif;
if ($json->data->photography_art_empty_space->title_position == "right"):
?>
.block.photography_art_empty_space {
  justify-content: flex-end;
}
.block.photography_art_empty_space h1 {
  margin-right: 15%;
}
<?php
endif;
$github = $json->data->work->color;
?>
/*#wo_github.content_block:hover {
    background-color: hsla(<?php echo $github[0] . ", " . $github[1] . "%, " . $github[2] . "%, " . $github[3]; ?>);
    border: 5px solid hsla(<?php echo $github[0] . ", " . $github[1] . "%, " . $github[2] . "%, " . $github[3]; ?>);
} #wo_github.content_block:hover .content_name span {
    background-color: hsla(<?php echo $github[0] . ", " . $github[1] . "%, " . $github[2] . "%, " . $github[3]; ?>);
}*/

<?php
if (!empty($json->data->work_empty_space->filter)):
?>
.block.work_empty_space {
  filter: <?php echo $json->data->work_empty_space->filter; ?>;
}
<?php
endif;
if (!empty($json->data->photography_art_empty_space->filter)):
?>
.block.photography_art_empty_space {
  filter: <?php echo $json->data->photography_art_empty_space->filter; ?>;
}
<?php
endif;
if (!empty($json->data->front_page->filter)):
?>
.block.front_page {
  filter: <?php echo $json->data->front_page->filter; ?>;
}
<?php
endif;
if (!empty($json->data->social_media_empty_space->filter)):
?>
.block.social_media_empty_space {
  filter: <?php echo $json->data->social_media_empty_space->filter; ?>;
}
<?php
endif;
?>