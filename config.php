<?php
//SETUP:
$dbname = 'phpwpush'; //The desired MySQL database name.
$dbuser = 'phpw';  //The credential lookup user
$dbpass = 'phpass!3#.';  //The credential lookup user's password
$key = "change this key to something else"; // Just change this to something random.
$xtime_default = 15; // default minutes until expires
$xviews_default = 2; // default # of views before it expires
$logopath="/your/image/here.jpg";
$warning='Submitted credentials will expire and be erased according to the time or view limit, whichever occurs first.';
?>