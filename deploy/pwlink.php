<?php
require 'config.php'; 
require 'database.php';
require 'mail.php';
require 'encryption.php';
require 'sanitize.php';
require 'interface.php';

print PrintHeader();

if(!SanitizeInput()){
    print('<form action="' . $_SERVER['PHP_SELF'] . '" method="post"><table>' .
//TODO: config for auth services
//    <tr><td>Sender:</td><td>" . $_SERVER['PHP_AUTH_USER'] . "</td></tr> 
    '<tr><td>Credentials:</td><td><textarea name="cred" /></textarea></td></tr>
    <tr><td>Time Limit:<td><input type="text" size="5" name="minutes" value="30" /> minutes</td></tr>
    <tr><td>View Limit:<td><input type="text" size="5" name="views" value="2" /> views</td></tr>' .
//TODO: config for mailing service
//    <tr><td>Destination Email:</td><td><input type='text' name='destemail' /></td></tr>
    '<tr><td><p><input type="submit" value="Submit" /></p></td></tr>
    </table></form>');
} else {
    //error_log($cred);
    $encrypted = EncryptCred($cred);
	$id = md5(uniqid());
    InsertCred($id,$encrypted,$xtime,$xviews);
    $url = sprintf("https://%s%s/%s?id=%s", $_SERVER['HTTP_HOST'], $installation, 'pwretrieve.php', $id);
    //MailURL($url);  //TODO: config for mailing service
	PrintURL($url);     
    PrintWarning($submitwarning);
}
print PrintFooter();
?>