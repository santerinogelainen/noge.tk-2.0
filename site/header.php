<!-- HEADER START -->

<header>
  <a href="<?php echo BASE_URL; ?>"><img id="logo" src="<?php echo $json->css->{'#logo'}->src?>" /></a>
  <ul id="header-links">
    <div class='header_row'>
    <li class="hl">
      <a class="information_link"><?php echo strtoupper($json->data->information->title) ?></a>
    </li>
    <li class="hl">
      <a class="projects_link"><?php echo strtoupper($json->data->work->title) ?></a>
    </li>
    <li class="hl">
     <a class="photography-art_link"><?php echo strtoupper($json->data->photography_art->title) ?></a>
    </li>
    <li class="hl">
      <a class="social-media_link"><?php echo strtoupper($json->data->social_media->title) ?></a>
    </li>
  </div>
    <?php
      if (login_check($mysqli)) {
        echo "
        <div class='header_row'>
          <li class='hl'>
            <a href='edit/save_all'>SAVE ALL CHANGES</a>
          </li>
          <li class='hl reset_changes'><a href='edit/save_reset'>RESET CHANGES</a></li>
          <li class='hl site_settings_button'><a>SITE SETTINGS</a></li>
          <li class='hl'><a href='edit'>EDIT MENU</a></li>
        </div>
        ";
      }
    ?>
  </ul>
  <img src="<?php echo $json->css->{'#hamburger'}->src?>" id='hamburger'/>
</header>

<!-- HEADER END -->
