<?php

//edit index

include "../define.php";

include ROOT . '/includes/functions.php';
include ROOT . '/site/json.php';

sec_session_start();

if (login_check($mysqli)):
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit index</title>
  <meta charset="UTF-8">
  <!-- COLORS -->
  <meta name="theme-color" content="#000000" />
  <meta name="msapplication-TileColor" content="#000000">
  <meta name="msapplication-navbutton-color" content="#000000">
  <meta name="apple-mobile-web-app-status-bar-style" content="#000000">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Lobster|Playfair+Display|Raleway|Roboto|Ubuntu|Yantramanav|VT323" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/edit_index.css" />
  <link rel="stylesheet" type="text/css" href="../css/general.css" />
  <link rel='stylesheet' type='text/css' href='../css/temp_phpstyles.php' />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script type="text/JavaScript" src="../js/login/sha512.js"></script>
  <script type="text/JavaScript" src="../js/login/forms.js"></script>
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
  <script src="../js/general.js"></script>
</head>
<body>
  <?php include ROOT . "/site/page_header.php";?>
  <form class="edit_form">
    <h1>Add</h1>
    <div class="add_buttons">
      <a href="content"><button type="button">Content page</button></a>
      <a href="post"><button type="button">Post page</button></a>
      <a href="image"><button type="button">Image page</button></a>
    </div>
    <h1>Register</h1>
    <div class="register">
      <span class='open_req'>&#x2304;</span><ul class="requirements">
          <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
          <li>Emails must have a valid email format</li>
          <li>Passwords must be at least 6 characters long</li>
          <li>Passwords must contain
              <ul>
                  <li>At least one upper case letter (A..Z)</li>
                  <li>At least one lower case letter (a..z)</li>
                  <li>At least one number (0..9)</li>
              </ul>
          </li>
      </ul>
      <form method="post" name="registration_form">
          Username: <input type='text' name='username' id='username' /><br>
          Email: <input type="text" name="email" id="email" /><br>
          Password: <input type="password"
                           name="password"
                           id="password"/><br>
          Confirm password: <input type="password"
                                   name="confirmpwd"
                                   id="confirmpwd" /><br>
          <input type="button"
                 value="Register"
                 onclick="return regformhash(this.form,
                                 this.form.username,
                                 this.form.email,
                                 this.form.password,
                                 this.form.confirmpwd);" />
      </form>
    </div>
  </form>
  <?php
  include ROOT . "/site/footer.php";
  ?>
  <script src='../js/edit_index.js'></script>
</body>
</html>
<?php
else:
  header("Location: ../error?status=401");
  exit();
endif;
?>
