<?php
//MySQL and Apache configuration:
$dbname = 'phpwpush'; //The desired MySQL database name.
$dbuser = 'phpw';  //The credential lookup user
$dbpass = 'phpass!3#.';  //The credential lookup user's password
$installation = '/phpw';  //The installation location (path to where PHPasswordPusher is hosted)

//Encryption and default limits:
$key = "change this key"; //This is the encryption key. Change it to something random.
$xtime_default = 15; //default minutes until the link expires
$xviews_default = 2; //default # of views before the link expires
$submitwarning = 'Submitted credentials will expire and be erased according to the time or view limit, '
  . 'whichever occurs first. NEVER leave credentials where they can be easily accessed by others.';
$retrievewarning = 'NEVER leave credentials where they can be easily accessed by others. '
  . '<!--We recommend using KeePass (<a href=\"http://keepass.info/\">http://keepass.info</a>).-->';

//User interface misc.:
$logoname = "phpwpusher.png"; // 
$title='Secure Password Sharing Utility';


?>