<?php //TODO: config for mailing service
//Mail the credentials to the recipient.
function MailURL($url) {
  require 'config.php';
  require 'input.php';

//TODO: config for auth services
//$sender = $_SERVER['PHP_AUTH_USER'];
  
  $headers = 'From: ' . $sender .'@ship.edu' . "\r\n";
  mail($destemail, $sender . ' sent you a credential. ', $url .
    "\r\n\nThis link contains sensitive information and will be inaccessible after " .
    CalcHRTime($xtime) . ' OR ' . $xviews . " views, whichever occurs first.
    \r\nNEVER leave credentials where they can be easily accessed. We recommend using KeePass (http://keepass.info/).",
     $headers) or die('Email send failed!');
  
  //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' . $destemail, '') or die('Email send failed!');
  sleep(1);
  print("Email sent to $destemail.");
}


?>