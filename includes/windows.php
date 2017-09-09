<?php if (login_check($mysqli)): ?>

<div class='window' id="post_settings">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>Settings:</h2>
  Custom page title?: <input type="checkbox" class="custom_page_title" <?php
  if ($result !== NULL && $result["title"] != $result["page_title"]) {
    echo "checked='checked'";
  }
  ?>/><br />
  Page title: <input type="text" class="page_title" <?php
  if ($result !== NULL) {
    echo "value='" . $result["page_title"] . "'";
  } else {
    echo "disabled='disabled'";
  }
  ?> /><br /><br />
  <h3>Options:</h3>
  Preview mode: <input type="checkbox" class="preview_mode" /> (cannot edit on visual side, links are clickable etc.)
  </form>
</div>

<div class='window' id="post_image">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>IMAGE</h2>
  <span class='warning'>Choose images only!</span>
  <div class='file_selection'>
    <div class='file_system_buttons'><img title='New folder' class='fs_new_folder' src='/svg/add_black.svg'><img title='Reload current folder' class='fs_reload_folder' src='/svg/reload_black.svg'><label><img title='Upload' class='fs_upload_button' src='/svg/upload_black.svg'><input class='upload_input' type='file' multiple/></label></div>
    <div class='file_system_loader'></div>
    <?php
     $filesystem = new FileSystem("/", 75);
     echo $filesystem->htmlCheckbox(true, "image");
    ?>
  </div>
  <h3>OR LINK IMAGES (HIGHER PRIORITY) <img title='Add another link' class='add_image_link' src='../svg/add_black.svg' /></h3>
  <div class='link_images'>
    <input type='url' />
  </div>
  <div class='choose_style'>
    <h2>CHOOSE STYLE</h2>
    <label class='image_slider'>
      <h3>Slider</h3>
      <input type='radio' name='image_style' value='slider' />
      <img src='../svg/slider.svg' />
    </label>
    <label class='image_thumbnail'>
      <h3>Thumbnail</h3>
      <input type='radio' name='image_style' value='thumbnail' />
      <img src='../svg/thumbnails.svg' />
    </label>
    <label class='image_full'>
      <h3>Full Image</h3>
      <input type='radio' name='image_style' value='full' />
      <img src='../svg/full_image.svg' />
    </label>
    <label class='image_banner'>
      <h3>Banner</h3>
      <input type='radio' name='image_style' value='banner' />
      <img src='../svg/banner_image.svg' />
    </label>
    <br />
    <h2>OVERWRITE STYLING</h2>
    Width:
    <input type='text' class='image_width' /><br />
    Height:
    <input type='text' class='image_height' /><br />
    Ignore parent padding?:
    <input type='checkbox' class='parent_padding' /> Yes
    <br />
    With padding-top?:
    <input type='checkbox' class='parent_padding_top' /> Yes
    <br />
    <div class="image_bg">
      <br />
      Background-color:
      <br />
      <input type='radio' name='slider_bg' value='white' class='slider_bg_white' /> White
      <br />
      <input type='radio' name='slider_bg' value='#eee' class='slider_bg_gray' /> #eee (gray)
      <br />
      <input type='radio' name='slider_bg' value='black' class='slider_bg_black' /> Black
    </div>
    <div class="image_autoplay">
      <br />
      Autoplay?: <input type='checkbox' class='slider_autoplay' />
      <br />
    </div>
    <div class="image_title_div">
      <br />
      Title: <input type='text' class='image_title' />
      <br />
    </div>
    <br />
  </div>
  <input type="button" class="post_edit_submit submit_image" value="Add" />
  </form>
</div>

<div class='window' id="post_file">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>FILE</h2>
  <div class='file_selection'>
    <div class='file_system_buttons'><img title='New folder' class='fs_new_folder' src='/svg/add_black.svg'><img title='Reload current folder' class='fs_reload_folder' src='/svg/reload_black.svg'><label><img title='Upload' class='fs_upload_button' src='/svg/upload_black.svg'><input class='upload_input' type='file' multiple/></label></div>
    <div class='file_system_loader'></div>
    <?php
     echo $filesystem->htmlCheckbox(true, "file");
    ?>
  </div>
  <input type="button" class="post_edit_submit submit_file" value="Add" />
  </form>
</div>

<div class='window' id="post_color">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>COLOR</h2>
  <?php
  $colorpicker = new ColorPicker(array(
      "hsla" => array(360, 0, 0, 1),
      "title" => "Color",
      "name" => "color"
  ));
  echo $colorpicker->hsla();
  ?>
  Or write a css value: <input type='text' class='color_text' />
  <input type="button" class="post_edit_submit submit_color" value="Add" />
  </form>
</div>

<div class='window' id="post_highlight">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>HIGHLIGHT</h2>
  <?php
  $colorpicker = new ColorPicker(array(
      "hsla" => array(360, 0, 0, 1),
      "title" => "Highlight",
      "name" => "highlight"
  ));
  echo $colorpicker->hsla();
  ?>
  Or write a css value: <input type='text' class='highlight_text' />
  <input type="button" class="post_edit_submit submit_highlight" value="Add" />
  </form>
</div>

<div class='window' id="post_link">
  <form>
  <img src="../svg/close.svg" class='close_window' />
  <h2>LINK</h2>
  Text: <input type='text' class='link_text' /><br />
  URL: <input type='text' class='link_url' /><br />
  Open in a new tab?: <input type='checkbox' class='link_new_tab' checked="checked"/><br /><br />
  <input type="button" class="post_edit_submit submit_link" value="Add" />
  </form>
</div>

<div class='window' id='post_video'>
  <form>
    <img src="../svg/close.svg" class='close_window' />
    <h2>VIDEO/IFRAME</h2>
    Link: <input type='text' class='video_link' /><br /><br />
    Width: <input type='text' class='video_width' /><br />
    Height: <input type='text' class='video_height' /><br /><br />
    Ignore padding: <input type='checkbox' class='video_ignore_padding' /><br /><br />
    <input type="button" class="post_edit_submit submit_video" value="Add" />
  </form>
</div>
<?php
else:
  header("../error?status=401");
  exit();
endif;
?>