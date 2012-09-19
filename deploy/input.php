<?php

//Grab arguments from POST
function GetArguments() {
  $arguments = Array();
  if (isset($_GET['id'])) { 
    $arguments['id'] = $_GET['id'];
    $arguments['func'] = 'get';
  }
  if (isset($_POST['cred'])) { 
    $arguments['cred'] = $_POST['cred']; 
    $arguments['func'] = 'post';
  }
  if (isset($_POST['minutes'])) {
    $arguments['minutes'] = $_POST['minutes'];
    $arguments['func'] = 'post';
  }
  if (isset($_POST['views'])) { 
    $arguments['views'] = $_POST['views'];
    $arguments['func'] = 'post';
  }
  if (isset($_POST['destemail'])) { 
    $arguments['destemail'] = $_POST['destemail'];
    $arguments['func'] = 'post';
  }
  if(!$arguments) {
     $arguments['func'] = 'none';
  }
  return $arguments;
}

//Sanitize all the inputs and determine their validity.
function CheckInput($arguments) {
     
  if(isset($arguments['cred'])) {
    $arguments['cred'] = SanitizeCred($arguments['cred']);
    if ($arguments['cred'] == false) {
      PrintError('Please input a valid credential!');
      return false;
    }
  }

  if (isset($arguments['minutes'])) {
    $arguments['minutes'] = SanitizeNumber($arguments['minutes']);
    if ($arguments['minutes'] == false) {
      PrintError('Please input a valid time limit (positive whole number)!');
      return false;
    }
  }

  if (isset($arguments['views'])) {
    $arguments['views'] = SanitizeNumber($arguments['views']);
    if ($arguments['views'] == false) {
      PrintError('Please input a valid view limit (positive whole number)!');
      return false;
    }
  }
//  TODO: config for mailing service
  // if (isset($arguments['destemail'])) {
    // $arguments['destemail'] = SanitizeEmail($arguments['destemail']);
    // if ($arguments['destemail'] == false) {
      // PrintError('Please input a valid email!');
      // return false;
    // }
  // }
  return $arguments;
}

//Check and Sanitize the user's email.
function SanitizeEmail($email)
{
  if (strlen($email) > 50 || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL )) {
    return false;
  } else {
    $email = strip_tags($email);
    $email = mysql_real_escape_string($email);
    return $email;
  }
}

//Sanitize number entry
function SanitizeNumber($number)
{

  if (!filter_var($number, FILTER_VALIDATE_INT) || $number < 0) {
    return false;
  }else {
    return $number;
  }
}

//Check and Sanitize the user's credentials.
function SanitizeCred($cred) {
  if (empty($cred)){
    return false;
  }else {
    $cred = htmlspecialchars($cred);
    return $cred;
  }
}

?>