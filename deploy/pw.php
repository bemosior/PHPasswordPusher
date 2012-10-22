<?php
require 'config.php'; 
require 'database.php';
require 'mail.php';
require 'encryption.php';
require 'input.php';
require 'interface.php';

print PrintHeader();

//Find user arguments, if any.
$arguments = GetArguments();
$arguments = CheckInput($arguments);  

if($requireAuth && empty($_SERVER['PHP_AUTH_USER'])){  //Die if auth is required and no user is defined.
  //This is a courtesy; PHP_AUTH_USER can possibly be spoofed if web auth isn't configured.
  printError("User not authenticated!");
  printFooter();
  die();
} 

if($arguments['func'] == 'none'){  //If no arguments exist, print the form for the user.
  print('<div class="hero-unit"><h2>Create the credential:</h2> <form action="' . $_SERVER['PHP_SELF'] . '" method="post">');

  if($enableEmail && $requireAuth) {  //Display creator username if email and authentication are configured.
      print('<label class="control-label" for="destemail">Sender: ' . $_SERVER['PHP_AUTH_USER'] . '</label>'); 
  }
  
  print('
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span>
                <textarea rows="3" placeholder="Credential" name="cred" /></textarea>
              </div>
            </div>

            <div class="controls">
              <div class="input-prepend input-append">
                <span class="add-on"><i class="icon-time"></i></span>
                <input class="span1" type="text" placeholder="30" name="minutes" />
                <span class="add-on">minutes</span>
              </div>
            </div>

            <div class="controls">
              <div class="input-prepend input-append">
                <span class="add-on"><i class="icon-eye-open"></i></span>
                <input class="span1" type="text" placeholder="2" name="views" />
                <span class="add-on">views</span>
              </div>
            </div>
            
            
            ');
  if($enableEmail) {  //Display field for destination email if enabled.
      print('            
            <label class="control-label" for="destemail">Destination Email:</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span>
                <input type="text" placeholder="email@yourdomain.com" name="destemail" />
              </div>
            </div>
      ');
  }
  
  print('<input class="btn btn-primary btn-large" type="submit" value="Submit" /></div>');
  
} elseif($arguments['func'] == 'post') { //If POST arguments exist and have been verified, process the credential
  $encrypted = EncryptCred($arguments['cred']); //Encrypt the user's credential.
  unset($arguments['cred']);  //Wipe out the variable with the credential.
  $id = md5(uniqid());  //Create a unique identifier for the new credential record.
  InsertCred($id,$encrypted,$arguments['minutes'],$arguments['views']);  //Insert the record into the database.
  $url = sprintf("https://%s%s?id=%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'], $id);  //Generate the retrieval URL.
  
  if($enableEmail) { //Send mail if configured.
      MailURL($url,$arguments['destemail'], CalcHRTime($arguments['minutes']), $arguments['views']); 
  }  
  
  if ($displayURL) { 
    PrintURL($url); 
    PrintWarning($submitwarning);
  } else { PrintWarning('Credential Created!'); } //Print the URL and associated functions
  
    
} elseif($arguments['func'] == 'get') {  //If GET arguments exist and have been verified, retrieve the credential
  $result = RetrieveCred($arguments['id']);   
  print('<div class="hero-unit">');
  if(empty($result[0])) {  //If no valid entry, deny access and wipe hypothetically existing records
    print('<h2>Woops!</h2>');
    PrintError('Link Expired');
  } else {
    $cred = DecryptCred($result[0]['seccred']);  //Decrypt the credential
    PrintCred($cred);  //Print credentials
    unset($cred);
    // PrintWarning($retrievewarning);  //Print warning
  }
  print('</div>');
}
print PrintFooter();
?>