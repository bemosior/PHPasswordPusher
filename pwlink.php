<?php

require 'config.php';

$db = new PDO('mysql:dbname=' . $dbname . ';host=localhost', $dbuser, $dbpass) or die('Connect Failed');
$xtime = $xviews = $cred = $destemail = $url = '';

//TODO: config for auth services
//$sender = $_SERVER['PHP_AUTH_USER'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
      Secure Credentials
    </title>
  </head>
  <body>
    <img src="<?php print $logopath; ?>">
    <h2>Credential Sharing Utility</h2>

<?php
if(!SanitizeInput()){
    print("<form action='" . $_SERVER['PHP_SELF'] . "' method='post'><table>" .
//TODO: config for auth services
//    <tr><td>Sender:</td><td>" . $_SERVER['PHP_AUTH_USER'] . "</td></tr> 
    "<tr><td>Credentials:</td><td><input type='text' name='cred' /></td></tr>
    <tr><td>Time Limit:<td><input type='text' size='5' name='minutes' value='30' /> minutes</td></tr>
    <tr><td>View Limit:<td><input type='text' size='5' name='views' value='2' /> views</td></tr>" .
//TODO: config for mailing service
//    <tr><td>Destination Email:</td><td><input type='text' name='destemail' /></td></tr>
    "<tr><td><p><input type='submit' value='Submit' /></p></td></tr>
    </table></form>");

} else {
    EncryptCred();
	
	//TODO: config for mailing service
    //MailCred();
	PrintCred();

}

print '<p>' . $warning . '</p>';

//Functions

//Sanitize number entry
function SanitizeNumber($number)
{

  if (!filter_var($number, FILTER_VALIDATE_INT) || $number < 0) {
    return false;
  }else {
    return $number;
  }
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


//Encrypt the credential into the database and generate the access URL.
function EncryptCred() {
  global $db, $key, $cred, $xtime_default, $xviews_default, $xtime, $xviews, $url;

  $encrypted = base64_encode(
        mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256, $key, $cred, MCRYPT_MODE_ECB,
            mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
        )
    );

    $query = "insert into phpasspush(id,seccred,ctime,views,xtime,xviews) values
      (:id, :seccred, UTC_TIMESTAMP(), 0, UTC_TIMESTAMP()+ INTERVAL :xtime MINUTE, :xviews)";
    $params = array(
        'id'        => md5(uniqid()),
        'seccred'  => $encrypted,
        'xtime'     => "+" . (is_numeric($xtime) ? $xtime : $xtime_default) . " minutes",
        'xviews'    => is_numeric($xviews) ? $xviews : $xviews_default,
    );

    $url = sprintf("https://%s/%s?id=%s", $_SERVER['HTTP_HOST'], 'pwretrieve.php', $params['id']);

    if(!$statement = $db->prepare($query)){
      die('Prep Failure!');
    }
    $statement->execute($params) or die("Execution Failure");
    return $url;
  }

//TODO: config for mailing service
//Mail the credentials to the recipient.
function MailCred() {
    global $destemail, $url, $sender, $xtime, $xviews;
    $headers = 'From: ' . $sender .'@ship.edu' . "\r\n";
    mail($destemail, $sender . ' sent you a credential. ', $url .
      "\r\n\nThis link contains sensitive information and will be inaccessible after " .
      CalcHRTime($xtime) . ' OR ' . $xviews . " views, whichever occurs first.
      \r\nNEVER leave credentials where they can be easily accessed. We recommend using KeePass (http://keepass.info/).",
       $headers) or die('Email send failed!');

    //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' . $destemail, '') or die('Email send failed!');
    sleep(1);
    print("Email sent to $destemail.");

}

function PrintCred() {
    global $url, $sender, $xtime, $xviews;
    print( $url . "<br/><br/>This link contains sensitive information and will be inaccessible after " .
      CalcHRTime($xtime) . ' OR ' . $xviews . " views, whichever occurs first.
      <br/><br/>NEVER leave credentials where they can be easily accessed. We recommend using KeePass (http://keepass.info/).");


}

//Print errors to page
function PrintError($error) {
  print("<font color=\"FF0000\">$error</font>");
}

function CalcHRTime($minutes) {
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