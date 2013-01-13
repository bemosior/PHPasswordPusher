<?php 


//Mail the credentials to the recipient.
function MailURL($url, $destemail, $expirationTime, $expirationViews) {
require 'config.php';

$sender = 'phpw' . '@' . $assumedDomain; 
if (!empty($_SERVER['PHP_AUTH_USER']) && $enableSender == 1) { 
  $sender = $_SERVER['PHP_AUTH_USER'] . '@' . $assumedDomain; 
} 
  
  $headers = 'From: ' . $sender  . "\r\n";
  mail($destemail, $sender . ' sent you a credential. ', $url .
    "\r\n\nThis link contains sensitive information and will be inaccessible after " .
    $expirationTime . ' OR ' . $expirationViews . " views, whichever occurs first.
    \r\n$criticalWarning",
     $headers) or die('Email send failed!');
  
  //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' . $destemail, '') or die('Email send failed!');
  sleep(1);
  print getSuccess("Email sent to $destemail.");
}


?>