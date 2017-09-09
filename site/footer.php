<div class='contact_window'>
  <div class='contact_text'>
    <?php echo $json->data->essentials->contact; ?>
  </div>
  <img class='contact_close' src='/svg/close_white.svg' />
</div>

<div class='cookie_window'>
  <div class='cookie_long'>
    <?php echo $json->data->essentials->cookie_long; ?>
  </div>
  <span class='cookie_long_agree cookie_button' onclick='hideCookieWindow(); agreeCookies();'><?php echo $json->data->essentials->cookie_long_agree; ?></span>
  <span class='cookie_long_disagree cookie_button' onclick="disagreeCookies();"><?php echo $json->data->essentials->cookie_long_disagree; ?></span>
  <img class='cookie_close' onclick='hideCookieWindow();' src="/svg/close_white.svg" />
</div>

<footer>
  <div>
    <span class='copyright'>&copy; <?php echo $json->data->essentials->copyright . " " . date("Y"); ?></span><div class='footer_small'>
      <span class='footer_cookies footer_link' onclick='showCookieWindow();'>Cookies</span><span class='footer_contact footer_link'>Contact</span>
    </div>
  </div>
  <?php if (!login_check($mysqli)) { ?>
  <img id="locked" class="login-lock" src="<?php echo URI; ?>svg/lock_white.svg" />
  <?php } else { ?>
  <a href="<?php echo URI; ?>edit/logout"><img class="login-lock" src="<?php echo URI; ?>svg/logout_white.svg" /></a>
  <?php } ?>
</footer>

<?php

    if (isset($_GET['error'])) {
        echo '<p class="error">Error Logging In!</p>';
    }
    ?>
    <form id="login-form" action="edit/process_login" method="post" name="login_form">
        <img id="login-close" src="<?php echo $json->css->{'#login-close'}->src?>" />
        <div>EMAIL</div><input class="login-input" id="email" type="text" name="email" />
        <br />
        <div>PASSWORD</div><input class="login-input" id="password" type="password"
                         name="password"
                         id="password"/>
        <br />
        <input class="login-input" id="login-button" type="button"
               value="LOGIN"
               onclick="formhash(this.form, this.form.password);" />
    </form>

<?php
    if (login_check($mysqli) == true) {
                    echo '<p id="loggedin">Currently logged in as ' . htmlentities($_SESSION['username']) . '.</p>';
    }
?>
