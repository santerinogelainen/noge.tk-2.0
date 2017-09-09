
<?php

$param = array(
  "k" => $keys["osu"][0],
  "u" => "7197109"
);

$content = $json->fromURL("https://osu.ppy.sh/api/get_user", $param);
if ($content != false):

?>

<!-- OSU -->
<div class='content_block' id='osu'>
  <img src='svg/osu_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <span class='cb_info_desc cb_info_no_scroll'>
    <h3><?php echo $content[0]->username; ?></h3>
    <img src='<?php echo $json->css->{'.cb_info_button'}->src; ?>' class='cb_info_button cb_black' />
    <div class='osu_rank'>#<?php echo $content[0]->pp_rank; ?></div>
    <div class='osu_country_rank'>#<?php echo $content[0]->pp_country_rank; ?><img src='/svg/suomi.svg'></div>
    <br>
    <div class='osu_pp'>PP: <?php echo round($content[0]->pp_raw); ?>pp</div>
    <div class='osu_pc'>Play count: <?php echo $content[0]->playcount; ?></div>
    <div class='osu_acc'>Accuracy: <?php echo round($content[0]->accuracy, 1); ?>%</div>
  </span>
  <a target='_blank' href='https://osu.ppy.sh/u/7197109'>
  <div class='content_header'>
      <img class='content_pic' src='https://a.ppy.sh/7197109' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
      <span class="cb_name">
      Osu!
      </span>
    </h2>
  </a>
</div>
<?php
endif;
?>