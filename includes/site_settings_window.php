<?php

if (login_check($mysqli)) {
  echo "<div class='site_settings_window window'>";
  echo "<form method='post' class='site_settings_form' name='site_settings_form' action='edit/save_site_settings'>";
  echo "<img class='close' src='/svg/close.svg'/>";
  echo "<h4>Site Settings</h4>";
  foreach ($json->css as $key => $val) {
    echo "<h5>" . $key . " <span data-key='" . $key . "' class='settings_add_css'>(Add)</span><span class='settings_remove_css_all'>(Remove)</span></h5>";
    foreach ($val as $val_key => $value) {
      echo "<label class='settings_css'>";
      echo "<span class='setting_name'>" . $val_key . ":</span>";
      echo "<input name='css[" . $key . "][" . $val_key . "]' class='setting_input' type='text' value='" . $value . "' /><br />";
      echo "<span class='settings_remove_css' title='Remove'>&#x2715;</span>";
      echo "</label>";
    }
  }
  echo "<div class='settings_new_css'>New css selector</div>";
  echo "<h4>Essential Settings</h4>";
  foreach ($json->data->essentials as $key => $value) {
    echo "<span class='setting_name'>" . $key . ": </span>";
    echo "<textarea name='data[" . $key . "]' class='essential_settings'>" . $value . "</textarea><br />";
  }
  echo "<input type='submit' value='Save to Temp' />";
  echo "</form>";
  echo "</div>";
} else {
  echo "<h1>401</h1> Unauthorized";
  exit;
}

?>
