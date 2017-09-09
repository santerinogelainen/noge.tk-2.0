<!-- STEAM -->

<?php
$param = array(
  "steamids" => "76561198067134270",
  "key" => $keys["steam"][0]
);

$content = $json->fromURL("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/", $param);
if ($content != false):

?>

<div class='content_block' id='steam'>
  <img src='svg/steam_white.svg' class='cb_info_button' title='<?php echo $json->data->essentials->block_card_info_title; ?>' />
  <!-- HEADER -->
  <a target='_blank' href='http://steamcommunity.com/id/pinguu9999/'>
  <div class='content_header'>
    <img class='content_pic' src='<?php echo $content->response->players[0]->avatarfull; ?>' />
  </div>

  <!-- USERNAME -->

    <h3 class='content_name'>
      <span class='cb_name'>
      Steam
      </span>
    </h3>
  </a>
</div>
<?php
endif;
?>
