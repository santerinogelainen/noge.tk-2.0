<!-- YOUTUBE -->

<?php

$param = array(
  "part" => "snippet",
  "id" => "UCPEtpMuMht91_aJkmE_acZg",
  "key" => $keys["youtube"][0]
);

$content = $json->fromURL("https://www.googleapis.com/youtube/v3/channels", $param);
if ($content != false):

?>

<div class='content_block' id='youtube'>
  <img src='svg/youtube_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <!-- HEADER -->
  <a target='_blank' href='https://www.youtube.com/channel/UCPEtpMuMht91_aJkmE_acZg'>
  <div class='content_header'>
    <img class='content_pic' src='<?php echo $content->items[0]->snippet->thumbnails->medium->url; ?>' />
  </div>

  <!-- USERNAME -->

  <h3 class='content_name'>
    <span class="cb_name">
    YouTube
    </span>
  </h2>
  </a>
</div>
<?php
endif;
?>
