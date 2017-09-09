<?php

$content = $json->fromURL("https://api.github.com/users/santerinogelainen");
if ($content != false):
?>

<div class='content_block' id='wo_github'>
  <!-- HEADER -->
  <a target='_blank' href='<?php echo $content->html_url; ?>'>
  <div class='content_header'>
    <img class='content_pic' src='<?php echo $content->avatar_url; ?>' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
        GitHub
    </h3>
  </a>
</div>

<?php
endif;
?>
