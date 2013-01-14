<?php
/**
 * PHPasswordPusher MySQL table installer
 */

//SETUP:
$rootdbuser = 'root';  //Privileged user to perform database creation and privilege granting.
$rootdbpass = 'rootpw';  //Privileged user's password (please remove this value when finished!).

//STOP. Have you configured "pwpusher_private/config.php" yet?

require 'pwpusher_private/config.php';

//Create Database
try{
  $db = new PDO("mysql:dbname=;host=localhost", $rootdbuser, $rootdbpass) or die('Connect Failed');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->exec('CREATE DATABASE ' . $dbname . ';'); 
  $db->exec('USE ' . $dbname .';');
  $db->exec('CREATE TABLE phpasspush (seccred TEXT, id VARCHAR(128) NOT NULL PRIMARY KEY, ctime DATETIME, xtime DATETIME, views INT, xviews INT);') ;
  $db->exec('CREATE EVENT phpasspush_tidy ON SCHEDULE EVERY 5 MINUTE DO UPDATE ' . $dbname . '.phpasspush SET seccred=NULL WHERE xtime<UTC_TIMESTAMP() OR views>=xviews;');
  $db->exec('GRANT ALL PRIVILEGES ON ' . $dbname . '.* TO \'' . $dbuser . '\'@\'localhost\' IDENTIFIED BY \'' . $dbpass . '\';') ;
  echo "MySQL setup is successful!\n";
} catch (PDOException $e) {
  echo 'Problem: ' . $e->getMessage() . "\n";
}
?>