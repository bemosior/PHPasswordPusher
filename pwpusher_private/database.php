<?php
/**
 * Database operations
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */


/**
 * Insert the credential into the database
 *
 * @param string  $id              the ID string for the credential
 * @param string  $encrypted       the encrypted credential
 * @param integer $expirationTime  minutes until expiration
 * @param integer $expirationViews views until expiration
 *
 * @return none
 */
function insertCred($id, $encrypted, $expirationTime, $expirationViews) 
{
    include 'config.php';

    //Set up query
    $query = "insert into ".$tblname."(id,seccred,ctime,views,xtime,xviews)
                  values(
                      :id, 
                      :seccred, 
                      UTC_TIMESTAMP(), 
                      0, DATE_ADD(UTC_TIMESTAMP(), INTERVAL :xtime MINUTE), :xviews
                  )";
    $params = array(
                      'id'        => $id,
                      'seccred'   => $encrypted,
                      'xtime'     => is_numeric(
                              $expirationTime
                          ) ? $expirationTime : $expirationTimeDefault,
                      'xviews'    => is_numeric(
                            $expirationViews
                        ) ? $expirationViews : $expirationViewsDefault,
    );
    
    //Connect to database and insert data
    try{
        $db = connectDB();
        $statement = $db->prepare($query);
        $statement->execute($params);
        
        //Erase all expired entries for good measure.
        eraseExpired($db);
      
    } catch (PDOException $e) {
        /** @noinspection PhpToStringImplementationInspection */
        print getError(translate('databaseErrorGeneric'));
        error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
    }
}

/** 
 * Retrieve credentials from database
 *
 * @param string $id the ID string of the credential
 *
 * @return array $result|boolean failure
 */
function retrieveCred($id) 
{
    include 'config.php';

    $update_query = 'update ' . $tblname . ' set views=views+1 ' .
        'where id=:id and xviews>views';
    $select_query = 'select seccred,views,xtime,xviews from ' . $tblname .
        ' where id=:id and xtime>UTC_TIMESTAMP()';
    $params = array('id' => $id);
    
    //First update the view count
    try{ 
        $db = connectDB();
        $statement = $db->prepare($update_query);
        $statement->execute($params);
        
        //If views update fails, end immediately before printing credentials.
        if (! $statement->rowCount()) {  
            return false;
        }
        
        //Prepare and execute the retrieval query
        $statement = $db->prepare($select_query);
        $statement->execute($params);
        $result = $statement->fetchAll();
        
        //Erase all expired entries for good measure.
        eraseExpired($db);  
        return $result;
      
    } catch (PDOException $e) {
        /** @noinspection PhpToStringImplementationInspection */
        print getError(translate('databaseErrorGeneric'));
        error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
    }
    return false;
}



/**
 * Erase all records that have expired due to expiration time or view limit
 *
 * @param PDO $db database connection instance
 *
 * @return none
 */
function eraseExpired($db) 
{
    include 'config.php';

    $query = 'delete from ' . $tblname .
        ' where xtime < UTC_TIMESTAMP() or xviews <= views';
    try{
        $statement = $db->prepare($query);
        $statement->execute();
    } catch (PDOException $e) {
        /** @noinspection PhpToStringImplementationInspection */
        print getError(translate('databaseErrorGeneric'));
        error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
    }
}


/**
 * Remove a specific record
 *
 * @param string $id record to remove
 *
 * @return none
 */
function eraseCred($id) 
{
    include 'config.php';

    $query = 'delete from ' . $tblname . ' where id=:id';
    $params = array('id' => $id);
    try{
        $db = connectDB();
        $statement = $db->prepare($query);
        $statement->execute($params);
        /** @noinspection PhpToStringImplementationInspection */
        print getSuccess(translate('linkErased'));
    } catch (PDOException $e) {
        /** @noinspection PhpToStringImplementationInspection */
        print getError(translate('databaseErrorGeneric'));
        error_log('PHPassword DB Error: ' . $e->getMessage() . "\n");
    }
}


/**
 * Connect to the database
 *
 * @return PDO $db database connection instance
 */
function connectDB() 
{
    include 'config.php';
    $db = new PDO('mysql:dbname=' . $dbname . ';host='.$host, $dbuser, $dbpass)
        or die('Connect Failed');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
