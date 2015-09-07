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
  $db = new PDO("mysql:dbname=;host=".$host, $rootdbuser, $rootdbpass) or die('Connect Failed');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($db->query("SHOW DATABASES LIKE '" . $dbname . "'")->rowCount() > 0) {
    echo "Database already exists. You can cancel to change the database name or continue.\n";
    echo "IF YOU CONTINUE, YOUR EXISTING DATABASE WILL BE WIPED!\n";
    echo "Continue? [y/N] ";
    $h = fopen("php://stdin","r");
    $ln = fgets($h);
    if(trim($ln) != "y") {
      echo "Aborting.\n";
      exit;
    }
    echo "Deleting database...\n";
    $db->exec('DROP DATABASE ' . $dbname . ';');
  }
  $db->exec('CREATE DATABASE ' . $dbname . ';');
  $db->exec('USE ' . $dbname .';');
  if($db->query("SHOW TABLES LIKE '" . $tblname . "'")->rowCount() > 0) {
    echo "Table already exists. You can cancel to change the table name or continue.\n";
    echo "IF YOU CONTINUE, YOUR EXISTING TABLE WILL BE WIPED!\n";
    echo "Continue? [y/N] ";
    $h = fopen("php://stdin","r");
    $ln = fgets($h);
    if(trim($ln) != "y") {
      echo "Aborting\n.";
      exit;
    }
    echo "Deleting table...\n";
    $db->exec('DROP TABLE ' . $tblname . ';');
  }
  $db->exec('CREATE TABLE ' . $tblname . ' (seccred TEXT, id VARCHAR(128) NOT NULL PRIMARY KEY, ctime DATETIME, xtime DATETIME, views INT, xviews INT);') ;
  $db->exec('CREATE EVENT ' . $tblname . '_tidy ON SCHEDULE EVERY 1 MINUTE DO DELETE FROM ' . $dbname . '.' . $tblname . ' WHERE xtime<UTC_TIMESTAMP() OR views>=xviews;');
  $db->exec('SET GLOBAL event_scheduler = 1;');
  $db->exec('GRANT ALL PRIVILEGES ON ' . $dbname . '.* TO \'' . $dbuser . '\'@\'localhost\' IDENTIFIED BY \'' . $dbpass . '\';') ;
  $db->exec('FLUSH PRIVILEGES;');
  echo "MySQL setup is successful!\n";
} catch (PDOException $e) {
  echo 'Problem: ' . $e->getMessage() . "\n";
}