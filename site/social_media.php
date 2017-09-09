<div class="block2" id="social-media">
  <h2><?php echo strtoupper($json->data->social_media->title) ?></h2>
  <hr class='block_hr' />
  <div class='content_wrapper'>
  <div class='content sm_content'>
  <div class='loader'></div>
  </div>
  </div>
  <?php
  //if logged in add edit possibilities
  if (login_check($mysqli)) {
      $menu = new EditMenu(array(
        "data" => $json->data,
        "key" => "social_media",
        "social_media" => true,
        "title" => "title",
        "order" => "order"
      ));
      echo $menu->html();
  }
  ?>
</div>
<div class="fix_sm_header_link"></div>
