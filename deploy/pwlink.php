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

if(!$arguments){  //If no arguments exist, print the form for the user.
    print('<form action="' . $_SERVER['PHP_SELF'] . '" method="post"><table>' .
//TODO: config for auth services
//    <tr><td>Sender:</td><td>" . $_SERVER['PHP_AUTH_USER'] . "</td></tr> 
    '<tr><td>Credentials:</td><td><textarea rows="1" name="cred" /></textarea></td></tr>
    <tr><td>Time Limit:<td><input type="text" size="5" name="minutes" value="30" /> minutes</td></tr>
    <tr><td>View Limit:<td><input type="text" size="5" name="views" value="2" /> views</td></tr>' .
//TODO: config for mailing service
//    <tr><td>Destination Email:</td><td><input type='text' name='destemail' /></td></tr>
    '<tr><td><p><input type="submit" value="Submit" /></p></td></tr>
    </table></form>');
} else {  //Arguments exist and have been verified. Process the credential
    $encrypted = EncryptCred($arguments['cred']); //Encrypt the user's credential.
    unset($arguments['cred']);  //Wipe out the variable with the credential.
    $id = md5(uniqid());  //Create a unique identifier for the new credential record.
    InsertCred($id,$encrypted,$arguments['minutes'],$arguments['views']);  //Insert the record into the database.
    $url = sprintf("https://%s%s/%s?id=%s", $_SERVER['HTTP_HOST'], $installation, 'pwretrieve.php', $id);  //Generate the retrieval URL.
    //MailURL($url);  //TODO: config for mailing service
    
    PrintURL($url);  //Print the URL and associated functions
    PrintWarning($submitwarning);  //Print the submission warning
}
print PrintFooter();
?>