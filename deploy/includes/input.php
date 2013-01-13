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
  require 'config.php';
     
  //Check the credential
  if(isset($arguments['cred'])) {
    $arguments['cred'] = SanitizeCred($arguments['cred']);
    if ($arguments['cred'] == false) {
      $arguments['func'] = 'none';
      print getError('Please input a valid credential!');
      return false;
    }
  }

  //Check Minutes
  if (isset($arguments['minutes'])) {
    //Set to the default value if empty
    if(empty($arguments['minutes'])) {
      $arguments['minutes'] = $expirationTimeDefault;
    }
    //Sanitize the input
    $arguments['minutes'] = SanitizeNumber($arguments['minutes']);
    if ($arguments['minutes'] == false) { 
      print getError('Please input a valid time limit (positive whole number)!');
      return false;
    }
  }

  //Check Views
  if (isset($arguments['views'])) {
    //Set to the default value if empty
    if(empty($arguments['views'])) {
      $arguments['views'] = $expirationViewsDefault;
    }
    //Sanitize the input
    $arguments['views'] = SanitizeNumber($arguments['views']);
    if ($arguments['views'] == false) {
      print getError('Please input a valid view limit (positive whole number)!');
      return false;
    }
  }
  
  //Check Email
  if (isset($arguments['destemail'])) {
    $arguments['destemail'] = SanitizeEmail($arguments['destemail']);
    if ($arguments['destemail'] == false) {
      print getWarning('Please enter a valid email address!');
    }
    
    //Ignore if empty
    if(empty($arguments['views'])) {
      $arguments['destemail'] = '';
    }
  }
  return $arguments;
}

//Check and Sanitize the user's email.
function SanitizeEmail($email) {
  if (strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL )) {
    return false;
  } else {
    $email = strip_tags($email);
    $email = mysql_real_escape_string($email);
    return $email;
  }
}

//Sanitize number entry
function SanitizeNumber($number) {
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