<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include "../define.php";

include_once ROOT . '/includes/db_connect.php';
include_once ROOT . '/includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['p'])) {

  function get_browser_name($user_agent) {
  if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
  elseif (strpos($user_agent, 'Edge')) return 'Edge';
  elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
  elseif (strpos($user_agent, 'Safari')) return 'Safari';
  elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
  elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

  return 'Other';
  }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['p']; // The hashed password.

    if (login($email, $password, $mysqli) == true) {
        // Login success

        //get user information
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        if (isset($details->region)) {
          $region = $details->region;
        } else {
          $region = "Unknown";
        }if (isset($details->city)) {
          $city = $details->city;
        } else {
          $city = "Unknown";
        }if (isset($details->country)) {
          $country = $details->country;
        } else {
          $country = "Unknown";
        }if (isset($details->loc)) {
          $loc = $details->loc;
        } else {
          $loc = "Unknown";
        }

        //send email to my account
        $to = 'santeri.nogelainen@gmail.com';
        $subject = 'You have logged in to your account!';
        $message = '<html>
        <head>
        <title>You have (or someone has) logged in to your account!</title>
        </head>
        <body>
        <p>
        Information about login:
        </p>
        <table>
        <tr>
        <th>IP</th><th>Location</th><th>Browser</th>
        </tr>
        <tr>
        <td>
        ' . $ip . '
        </td>
        <td>
        ' . $city  . ', ' . $region . ', ' . $country . ', ' . $loc . '
        </td>
        <td>
        ' . get_browser_name($_SERVER['HTTP_USER_AGENT']) . '
        </td>
        </tr>
        </table>
        <p>
        If this was not you please act immidiately!
        </p>
        </body>
        </html>';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: noreply@nogelai.net' . "\r\n";
        $headers .= "To: santeri.nogelainen@gmail.com" . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        header("Location: " . BASE_URL);
        exit();
    } else {
        // Login failed
        header('Location: ' . BASE_URL . 'index.php?error=1');
        exit();
    }
} else {
    // The correct POST variables were not sent to this page.
    header('Location: ../error?status=400');
    exit();
}
