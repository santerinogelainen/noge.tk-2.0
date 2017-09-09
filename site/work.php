<div class="block2" id="projects">
  <h2 class='block_title'><?php echo strtoupper($json->data->work->title); ?></h2>
  <hr class='block_hr' />
  <div class='block_info wo_info'>
    <?php echo $json->data->work->info; ?>
  </div>
  <div class='wo_content content'>
    <?php
    include ROOT . "/site/github.php";
    foreach ($json->data->work->content as $key => $value) {
      echo "<div id='wo_" . $key . "' class='content_block'>";
      if (!empty($value->desc)) {
        echo "<img src='svg/add_white.svg' class='cb_info_button' title='" . $json->data->essentials->block_card_info_title . "' />";
        echo "<span class='cb_info_desc'><h3>" . $value->title . "</h3><img src='" . $json->css->{'.cb_info_button'}->src . "' class='cb_info_button cb_black' />" . $value->desc . "</span>";
      }
      echo "<a href='" . $value->href . "'>";
      foreach ($value->image as $img_href) {
        echo "<div class='content_pic' style='background-image: url(\"" . $img_href . "\");'></div>";
      }
      echo "<h3 class='content_name'>";
      echo $value->title;
      echo "</h3>";
      echo "</a>";
      if (login_check($mysqli)) {
        echo "<img class='editbutton cb_edit' src='svg/edit.svg' />";
      }
      echo "</div>";
      if (login_check($mysqli)) {
        $menu = new EditMenu(array(
          "data" => $json->data->work->content,
          "key" => $key,
          "name" => "work->content->" . $key,
          "title" => "title",
          "text" => array("href", "desc"),
          "image" => array(1, 75)
        ));
        echo $menu->html();
      }
    }
    ?>
  </div>
  <?php
  if (login_check($mysqli)) {
    $add = new AddMenu("work", $mysqli);
    echo $add->html();
    $menu = new EditMenu(array(
      "data" => $json->data,
      "key" => "work",
      "title" => "title",
      "remove" => "content",
      "text" => array("info"),
      "order" => "order"
    ));
    echo $menu->html();
  }
  ?>
</div>
