<?php
//MySQL and Apache configuration:
  $dbname = 'phpwpush'; //The MySQL database name.
  $dbuser = 'phpw';  //The MySQL user
  $dbpass = 'phpass!3#.';  //The MySQL user's password
  $installation = '/phpw';  //The installation location (path to where PHPasswordPusher is hosted)

//Encryption and security:
  $key = "change this key"; //This is the encryption key. Change it to something secret.
  $requireAuth = false;  //Require Apache user authentication.

//Email
  $assumedDomain = 'domain.edu';  //The assumed domain of an authenticated user and email sender
  $enableEmail = true;  //Allow the credentials to be sent via email at the web form.
  $enableSender = true; //Set the username as the email sender (requires authentication)
  //It is highly recommended that authentication be required when enabling the email functionality in order to prevent spam!
  //The above two settings result in the email appearing to have been sent by the authenticated user. For example,
  //if your apache auth username is 'user', and the assumed domain is 'your.domain', the sender would be 'user@your.domain'.
  
//User interface misc.:
  $logoname = "phpwpusher.png"; //Logo location relative to the deploy directory
  $title = 'PHPasswordPusher';
  $displayURL = true;  //Enable display of the URL to the user.
  $expirationTimeDefault = 15; //default minutes until the link expires
  $expirationViewsDefault = 2; //default # of views before the link expires
  $submitWarning = 'Submitted credentials will expire and be erased according to the time or view limit, ' 
    . 'whichever occurs first.'; //Displayed on form submission
  $criticalWarning = 'NEVER leave credentials where they can be easily accessed by others.'; //Displayed in every page and email.


?>