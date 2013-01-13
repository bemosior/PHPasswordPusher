<?php
require 'includes/config.php'; 
require 'includes/database.php';
require 'includes/mail.php';
require 'includes/encryption.php';
require 'includes/input.php';
require 'includes/interface.php';

//Print the header
print getHeader();

//Print the navbar
print getNavBar();

//Find user arguments, if any.
$arguments = GetArguments();
$arguments = CheckInput($arguments);  

//Die if auth is required and no user is defined.
if($requireAuth && empty($_SERVER['PHP_AUTH_USER'])){  
  //This is a courtesy; PHP_AUTH_USER can possibly be spoofed if web auth isn't configured.
  print getError("User not authenticated!");
  print getFooter();
  die();
} 

//If the form function argument doesn't exist, print the form for the user.
if($arguments['func'] == 'none' || $arguments == false){  

  //Get form elements
  print getFormElements();
  
  //Else if POST arguments exist and have been verified, process the credential
} elseif($arguments['func'] == 'post') { 

  //Encrypt the user's credential.
  $encrypted = EncryptCred($arguments['cred']); 
  
  //Wipe out the variable with the credential.
  unset($arguments['cred']);  
  
  //Create a unique identifier for the new credential record.
  $id = md5(uniqid());  
  
  //Insert the record into the database.
  InsertCred($id,$encrypted,$arguments['minutes'],$arguments['views']);  
  
  //Generate the retrieval URL.
  $url = sprintf("https://%s%s?id=%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'], $id);  
  
  //Send email if configured and if the email has been filled out
  if($enableEmail && !empty($arguments['destemail'])) {
      MailURL($url,$arguments['destemail'], calcExpirationDisplay($arguments['minutes']), $arguments['views']); 
  }  
  
  //If the URL is configured to be displayed print the URL and associated functions
  if ($displayURL) { 
    print getURL($url); 
    print getWarning($submitWarning);
  } else { 
    print getWarning('Credential Created!'); 
  } 
  
  //If GET arguments exist and have been verified, retrieve the credential
} elseif($arguments['func'] == 'get') {  
  $result = RetrieveCred($arguments['id']);   
  print('<div class="hero-unit">');
  
  //If no valid entry, deny access and wipe hypothetically existing records
  if(empty($result[0])) {  
    print('<h2>Sorry!</h2>');
    print getError('Link Expired');
    
    //Otherwise, return the credential.
  } else {
  
    //Decrypt the credential
    $cred = DecryptCred($result[0]['seccred']);  
    
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