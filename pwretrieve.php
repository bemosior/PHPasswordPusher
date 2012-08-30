<?php
require 'config.php';

//Instantiate database connection
$db = new PDO('mysql:dbname=' . $dbname . ';host=localhost', $dbuser, $dbpass) or die('Connect Failed');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    <img src="<?php print "$installation/$logoname"; ?>">
    <h2><?php print $title; ?></h2>
<?php
  //----- Lookup password
  if(isset($_GET['id'])) {
  
      //TODO: Properly set up try/catch block.
      $query = "select seccred,views from phpasspush where id=:id and xtime>UTC_TIMESTAMP() and xviews>views";
      $params = array("id"=>$_GET['id']);
      $statement = $db->prepare($query);
      $statement->execute($params) or die("oops");

      $result = $statement->fetchAll();
      //Deny access (no results), wipe hypothetically existing records
      if(!$result[0]) {
          $query = "update phpasspush set seccred = NULL where id=:id";
          $params = array("id"=>$_GET['id']);
          $statement = $db->prepare($query);
          $statement->execute($params);
          die("<br/><br/>Link Expired");
      }
      $password = mcrypt_decrypt(
          MCRYPT_RIJNDAEL_256,
          $key,
          base64_decode($result[0]['seccred']),
          MCRYPT_MODE_ECB,
          mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)
      );

      // Update the view counter before showing the password
      $query = "update phpasspush set views=views+1 where id=:id";
      $statement = $db->prepare($query);
      $statement->execute($params); //TODO: Add error handling that prevents password display on fail

      print("<br/><br/><table border=\"1\"><tr><td>$password</td></tr></table>");
      print("<p>This page contains sensitive information and will be inaccessible in the near future.</p>");
      print("<p>NEVER leave credentials where they can be easily accessed. We recommend using <a href='
          http://keepass.info'>KeePass</a>.</p>");
  }

  ?><br>
  </body>
</html>