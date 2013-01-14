<?php
/**
 * The main credential creation page.
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
require '../pwpusher_private/config.php'; 
require '../pwpusher_private/database.php';
require '../pwpusher_private/mail.php';
require '../pwpusher_private/encryption.php';
require '../pwpusher_private/input.php';
require '../pwpusher_private/interface.php';

//Print the header
print getHeader();

//Print the navbar
print getNavBar();

//Find user arguments, if any.
$arguments = getArguments();
$arguments = checkInput($arguments);  

//Die if auth is required and no user is defined.
if ($requireAuth && empty($_SERVER['PHP_AUTH_USER'])) {
    //This section is a courtesy check; PHP_AUTH_USER can possibly be spoofed 
    //if web auth isn't configured.
    print getError("User not authenticated!");
    print getFooter();
    die();
} 

//If the form function argument doesn't exist, print the form for the user.
if ($arguments['func'] == 'none' || $arguments == false) {  

    //Get form elements
    print getFormElements();
  
  
} elseif ($arguments['func'] == 'post') { 
    //Else if POST arguments exist and have been verified, process the credential
    //Encrypt the user's credential.
    $encrypted = encryptCred($arguments['cred']); 
    
    //Wipe out the variable with the credential.
    unset($arguments['cred']);  
    
    //Create a unique identifier for the new credential record.
    $id = md5(uniqid());  
    
    //Insert the record into the database.
    insertCred($id, $encrypted, $arguments['minutes'], $arguments['views']);  
    
    //Generate the retrieval URL.
    $url = sprintf(
        "https://%s%s?id=%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'], $id
    );  
    
    //Send email if configured and if the email has been filled out
    if ($enableEmail && !empty($arguments['destemail'])) {
        mailURL(
            $url, 
            $arguments['destemail'], 
            calcExpirationDisplay($arguments['minutes']), 
            $arguments['views']
        ); 
    }  
    
    //If the URL is configured to be displayed print the URL and associated functions
    if ($displayURL) { 
        print getURL($url); 
    } else { 
        print getSuccess('Credential Created!'); 
    } 
  
  
} elseif ($arguments['func'] == 'get') {  
    //If GET arguments exist and have been verified, retrieve the credential
    $result = retrieveCred($arguments['id']);   
    print('<div class="hero-unit">');
    
    //If no valid entry, deny access and wipe hypothetically existing records
    if (empty($result[0])) {  
        print('<h2>Sorry!</h2>');
        print getError('Link Expired');
      
      
    } else {
        //Otherwise, return the credential.
        //Decrypt the credential
        $cred = decryptCred($result[0]['seccred']);  
        
        //Print credentials
        print getCred($cred);  
        
        //Unset the credential variable
        unset($cred);
    }
    print('</div>');
}

//Print the footer
print getFooter();
?>