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
	  EraseExpired($db);  //Erase all expired entries for good measure.
  } catch (PDOException $e) {
    error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
  }
}

//Retrieve credentials from database
function RetrieveCred($id) {
	$update_query = "update phpasspush set views=views+1 where id=:id and xviews>views";
  $select_query = "select seccred,views from phpasspush where id=:id and xtime>UTC_TIMESTAMP()";
  $params = array('id' => $id);
  try{ //First update the view count
    $db = ConnectDB();
    $statement = $db->prepare($update_query);
    $statement->execute($params);
    if (! $statement->rowCount()) {  //If views update fails, end immediately before printing credentials.
      return false;
    }
    $statement = $db->prepare($select_query);
    $statement->execute($params);
    $result = $statement->fetchAll();
    EraseExpired($db);  //Erase all expired entries for good measure.
    return $result;
  } catch (PDOException $e) {
    error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
  }
  return false;
}


//Erase all records that have expired due to expiration time or view limit
function EraseExpired($db) {

  $query = "delete from phpasspush where xtime < UTC_TIMESTAMP() or xviews <= views";
  try{
    $statement = $db->prepare($query);
    $statement->execute();
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