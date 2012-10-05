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
  . 'whichever occurs first.';
$retrievewarning = 'NEVER leave credentials where they can be easily accessed by others.';

//Email
$enableEmail = 0;  //Enable email functionality. 
//It is highly recommended that authentication be required as well to prevent spam!

$enableSender = 0;  //Enable user-specific senders
$assumedDomain = 'domain.edu';  //The assumed domain of an authenticated user. If no auth, phpw@assumeddomain.
//The above settings result in the email being sent by 'user@assumedDomain'. Valid addresses are recommended.

  
  
//User interface misc.:
$logoname = "phpwpusher.png"; //Relative to the deploy directory
$title='Secure Password Sharing Utility';
$displayURL = 1;  //Enable display of the URL to the user.
$requireAuth = 0;  //Require user authentication.

?>