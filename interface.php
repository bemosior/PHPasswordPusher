<?php

function PrintHeader() {
require 'config.php';
return '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>' . $title . '</title>
  </head>
  <body>
    <img src="' . $installation . '/' . $logoname . '">
    <h2>' . $title . '</h2>';
}

function PrintFooter() {
require 'config.php';
return '</body></html>';

}
//Print the credential URL
function PrintUser($info, $warning) {
  print("<br/><table border=\"1\"><tr><td>$info</td></tr></table><br/>"); 
  print $warning;
}

//Print errors to page
function PrintError($error) {
  print("<font color=\"FF0000\">$error</font>");
}

//Calculate the expiration time
function CalcTime($minutes) {
  $d = floor ($minutes / 1440);
  $h = floor (($minutes - $d * 1440) / 60);
  $m = $minutes - ($d * 1440) - ($h * 60);

  $HRTime = '';
  if ($d > 0) {
    $HRTime .= "$d day";
    if($d > 1) {
      $HRTime .= 's';
    }
  }
  if ($d > 0 && ($h + $m) > 0) {
    $HRTime .= ' + ';
  }
  if ($h > 0) {
    $HRTime .= "$h hour";
    if($h > 1) {
      $HRTime .= 's';
    }
  }
  if ($h > 0 && $m > 0) {
    $HRTime .= ' + ';
  }
  if ($m > 0) {
    $HRTime .= "$m minute";
    if($m > 1) {
      $HRTime .= 's';
    }
  }
  return $HRTime;
}
?>