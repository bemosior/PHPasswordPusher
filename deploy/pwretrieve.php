<?php
require 'config.php';
require 'database.php';
require 'interface.php';
require 'encryption.php';
require 'input.php';

print PrintHeader();

//Find user arguments, if any.
$arguments = GetArguments();

if($arguments) {  //Attempt to look up record by its ID
  $result = RetrieveCred($arguments['id']);   
  if(empty($result[0])) {  //If no valid entry, deny access and wipe hypothetically existing records
    PrintError('Link Expired');
  } else {
    $cred = DecryptCred($result[0]['seccred']);  //Decrypt the credential
    PrintCred($cred);  //Print credentials
    unset($cred);
    PrintWarning($retrievewarning);  //Print warning
  }
}
print PrintFooter();
?>