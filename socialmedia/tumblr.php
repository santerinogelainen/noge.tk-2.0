<?php

$param = array(
  "api_key" => $keys["tumblr"][0],
  "limit" => "4"
);

$content = $json->fromURL("https://api.tumblr.com/v2/blog/ttypical.tumblr.com/posts/photo", $param);

if ($content != false):
?>


<!-- TUMBLR -->

<div class='content_block' id='tumblr'>
  <img src='svg/tumblr_corner_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <span class='cb_info_desc cb_info_no_scroll'>
    <h3><?php echo $content->response->blog->name; ?></h3>
    <img src='<?php echo $json->css->{'.cb_info_button'}->src; ?>' class='cb_info_button cb_black' />
    <div class='cb_info_img'>
      <?php
      
      foreach($content->response->posts as $image) {
        echo "<a href='" . $image->post_url . "'><img src='" . end($image->photos[0]->alt_sizes)->url . "'></a>";
      }
      
      ?>
    </div>
  </span>
  <a target='_blank' href='<?php echo $content->response->blog->url; ?>'>
  <div class='content_header'>
      <img class='content_pic' src='https://api.tumblr.com/v2/blog/<?php echo $content->response->blog->name; ?>.tumblr.com/avatar/512' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
      <span class="cb_name">
      Tumblr
      </span>
    </h2>
  </a>
</div>
<?php
endif;
?>
