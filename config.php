<?php
//SETUP:
$dbname = 'phpwpush'; //The desired MySQL database name.
$dbuser = 'phpw';  //The credential lookup user
$dbpass = 'phpass!3#.';  //The credential lookup user's password
$installation = '/phpwpusher';  //The installation location (path to where PHPasswordPusher is hosted)
$key = "change this key"; // Just change this to something random.
$xtime_default = 15; // default minutes until expires
$xviews_default = 2; // default # of views before it expires
$logoname="phpwpusher.png";
$warning='Submitted credentials will expire and be erased according to the time or view limit, whichever occurs first.';
$title='Secure Password Sharing Utility';
?>