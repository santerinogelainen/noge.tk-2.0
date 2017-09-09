<div id="hamburger-menu">
  <ul id="header-links-hm">
      <div class='header_row'>
    <li class="hl">
      <a class="information_hm"><?php echo strtoupper($json->data->information->title);?></a>
    </li>
    <li class="hl">
      <a class="projects_hm"><?php echo strtoupper($json->data->work->title);?></a>
    </li>
    <li class="hl">
     <a class="photography-art_hm"><?php echo strtoupper($json->data->photography_art->title);?></a>
    </li>
    <li class="hl">
      <a class="social-media_hm"><?php echo strtoupper($json->data->social_media->title);?></a>
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
</div>
