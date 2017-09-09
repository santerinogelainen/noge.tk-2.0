<!-- INSTAGRAM -->

<?php

$param = array(
  "access_token" => $keys["instagram"][0]
);

$content = $json->fromURL("https://api.instagram.com/v1/users/self/", $param);

$param = array(
  "access_token" => $keys["instagram"][0],
  "count" => "4"
);

$images = $json->fromURL("https://api.instagram.com/v1/users/self/media/recent", $param);

if ($content != false && $images != false):
?>

<div class='content_block' id='instagram'>
  <img src='svg/instagram_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <span class='cb_info_desc cb_info_no_scroll'>
    <h3><?php echo $content->data->username; ?></h3>
    <img src='<?php echo $json->css->{'.cb_info_button'}->src; ?>' class='cb_info_button cb_black' />
    <div class='cb_info_img'>
      <?php
      
      foreach($images->data as $image) {
        echo "<a href='" . $image->link . "'><img src='" . $image->images->thumbnail->url . "'></a>";
      }
      
      ?>
    </div>
  </span>
  <a target='_blank' href='https://www.instagram.com/santerinogelainen/'>
  <div class='content_header'>
    <img class='content_pic' src='<?php echo $content->data->profile_picture; ?>' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
      <span class="cb_name">
      Instagram
      </span>
    </h2>
  </a>
</div>

<?php
endif;
?>
