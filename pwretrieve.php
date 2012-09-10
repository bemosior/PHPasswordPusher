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
      //Deny access (no results), wipe hypothetically existing records
      if(!$result[0]) {
        NullRecord($id);
        print ('<p>Link Expired</p>');
      }else {
        $cred = DecryptCred($result[0]['seccred']);
        ViewCred($id); //TODO: Add error handling that prevents password display on fail
        //PrintUser(nl2br(htmlspecialchars(stripslashes($cred))),$retrievewarning);
		PrintUser('<pre>' . $cred . '</pre>',$retrievewarning);
		
        // print("<script>window.prompt ('Copy to clipboard: Ctrl+C, Enter', '$cred');</script>"); //TODO: Clipboard functionality
      }
  }
  print PrintFooter();
?>