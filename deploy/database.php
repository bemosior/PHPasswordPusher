<?php
//Insert the credential into the database
function InsertCred($id, $encrypted, $xtime, $xviews) {
  //Connect to database and insert credential
  $query = "insert into phpasspush(id,seccred,ctime,views,xtime,xviews) values
      (:id, :seccred, UTC_TIMESTAMP(), 0, UTC_TIMESTAMP()+ INTERVAL :xtime MINUTE, :xviews)";
  $params = array(
      'id'        => $id,
      'seccred'  => $encrypted,
      'xtime'     => "+" . (is_numeric($xtime) ? $xtime : $xtime_default) . " minutes",
      'xviews'    => is_numeric($xviews) ? $xviews : $xviews_default,
  );
  
  //Connect to database and insert data
  try{
    $db = ConnectDB();
    $statement = $db->prepare($query);
    $statement->execute($params);
  } catch (PDOException $e) {
    error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
  }
}

//Retrieve credentials from database
function RetrieveCred($id) {
  $query = "select seccred,views from phpasspush where id=:id and xtime>UTC_TIMESTAMP() and xviews>views";
  $params = array('id' => $id);
  try{
    $db = ConnectDB();
    $statement = $db->prepare($query);
    $statement->execute($params);
    $result = $statement->fetchAll();
  } catch (PDOException $e) {
    error_log('PHPasswordPusher DB Error: ' . $e->getMessage() . "\n");
  }
  return $result;
}

//Increment the view count for a credential
function ViewCred($id) {
// Update the view counter before showing the password
  $query = "update phpasspush set views=views+1 where id=:id";
  $params = array('id' => $id);
  try{
    $db = ConnectDB();
    $statement = $db->prepare($query);
    $statement->execute($params);
  } catch (PDOException $e) {
    error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
  }
 
}


function NullRecord($id) {

  $query = "update phpasspush set seccred = NULL where id=:id";
  $params = array('id' => $id);
  try{
    $db = ConnectDB();
    $statement = $db->prepare($query);
    $statement->execute($params);
  } catch (PDOException $e) {
    error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
  }
 
}

//Connect to the database
function ConnectDB() {
  require 'config.php';
  $db = new PDO('mysql:dbname=' . $dbname . ';host=localhost', $dbuser, $dbpass) or die('Connect Failed');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $db;
}
?>