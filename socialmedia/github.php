<?php

$content = $json->fromURL("https://api.github.com/users/santerinogelainen");
if ($content != false):
?>

<div class='content_block' id='github'>
  <img src='svg/github_corner_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <!-- HEADER -->
  <a target='_blank' href='<?php echo $content->html_url; ?>'>
  <div class='content_header'>
    <img class='content_pic' src='<?php echo $content->avatar_url; ?>' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
      <span class="cb_name">
        GitHub
      </span>
    </h2>
  </a>
</div>

<?php
endif;
?>
