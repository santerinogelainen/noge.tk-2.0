<div class="block2" id="information">
  <h2><?php echo strtoupper($json->data->information->title) ?></h2>
  <hr class='block_hr' />
  <div id="profile_pic">
    <?php
      echo "<img src='" . $json->data->information->image[0] . "' />";
      echo "<h3>" . $json->data->information->profile_pic_text . "</h3>"
    ?>
  </div>
  <div id="desc">
    <div id="bio">
      <h3><?php echo $json->data->information->bio_title; ?></h3>
      <p><?php echo $json->data->information->bio; ?></p>
      <div class="button" id="me"><?php echo $json->data->information->more_me; ?></div>
      <br />
      <h3 class="hide"><?php echo $json->data->information->about_me_title; ?></h3>
      <table class="hide">
      <?php
        foreach ($json->data->information->about_me as &$value) {
          $rowM = $value;
          echo "<tr>";
          foreach ($rowM as &$val) {
            echo "<td>" . $val . "</td>";
          }
          echo "</tr>";
        }
      ?>
      </table>
    </div>
    <div id="website">
      <h3><?php echo $json->data->information->web_title; ?></h3>
      <p><?php echo $json->data->information->web_desc; ?></p>
      <div class="button" id="web"><?php echo $json->data->information->more_web; ?></div>
      <br />
      <h3 class="hide"><?php echo $json->data->information->about_web_title; ?></h3>
      <table class="hide">
      <?php
      foreach ($json->data->information->about_web as &$value) {
        $rowW = $value;
        echo "<tr>";
        foreach ($rowW as &$val) {
          echo "<td>" . $val . "</td>";
        }
        echo "</tr>";
      }
      ?>
      </table>
    </div>
  </div>
  <?php
  //if logged in add edit possibilities
  if (login_check($mysqli)) {
    $menu = new EditMenu(array(
      "data" => $json->data,
      "key" => "information",
      "title" => "title",
      "text" => array("bio_title", "bio", "web_title", "web_desc", "profile_pic_text", "more_me", "more_web", "about_me_title", "about_web_title"),
      "image" => array(1, 75),
      "table" => array("about_me", "about_web")
    ));
    echo $menu->html();
  }
  ?>
</div>
