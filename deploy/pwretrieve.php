<?php
require 'config.php';
require 'database.php';
require 'interface.php';
require 'encryption.php';

print PrintHeader();
  //----- Lookup password
  if(isset($_GET['id'])) {
      $id = $_GET['id'];
      $result = RetrieveCred($id);   
      if(empty($result[0])) {  //If no valid entry, deny access and wipe hypothetically existing records
        NullRecord($id);
        PrintError('<p>Link Expired</p>');
      }else {
        $cred = DecryptCred($result[0]['seccred']);
        ViewCred($id);  //TODO: Add error handling that prevents password display on fail
    PrintCred($cred);  //Print credentials
    PrintWarning($retrievewarning);  //Print warning
        // print("<script>window.prompt ('Copy to clipboard: Ctrl+C, Enter', '$cred');</script>"); //TODO: Clipboard functionality
      }
  }
  print PrintFooter();
?>