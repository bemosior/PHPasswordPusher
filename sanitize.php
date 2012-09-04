<?php
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
    return $cred;
  }
}

//Sanitize all the inputs and determine their validity.
function SanitizeInput() {
  global $cred, $destemail, $xtime, $xviews;

  if(isset($_POST['cred'])) {
    $cred = SanitizeCred($_POST['cred']);
    if ($cred == false) {
      PrintError('Please input a valid credential!');
      return false;
    }
  } else { return false; }

  if (isset($_POST['minutes'])) {
    $xtime = SanitizeNumber($_POST['minutes']);
    if ($xtime == false) {
      PrintError('Please input a valid time limit (positive whole number)!');
      return false;
    }
  } else { return false; }

  if (isset($_POST['views'])) {
    $xviews = SanitizeNumber($_POST['views']);
    if ($xviews == false) {
      PrintError('Please input a valid view limit (positive whole number)!');
      return false;
    }
  } else { return false; }
//  TODO: config for mailing service
  // if (isset($_POST['destemail'])) {
    // $destemail = SanitizeEmail($_POST['destemail']);
    // if ($destemail == false) {
      // PrintError('Please input a valid email!');
      // return false;
    // }
  // } else { return false; }
  return true;
}

?>