<?php
/**
 * Configuration Options
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
//MySQL and Apache configuration:

    //The MySQL database name.
    $dbname = 'phpwpush';
    
    //The MySQL user
    $dbuser = 'phpw';  
    
    //The MySQL user's password
    $dbpass = 'phpass!3#.';  
        
//Encryption and security:
    //The encryption key for the credentials. Change it to something secret.
    $key = 'change this key please!'; 
    
    //The salt for the ID hash. Change it to something secret (not the $key).
    //This field needs to be 22 alphanumberic characters. Anything past 22
    //will be cut off.
    $salt = 'changethiskey098please';
    
    //Require Apache user authentication.
    $requireAuth = false;  
    
    //Maximum life of a shared credential/password (in minutes).
    $credMaxLife = (60 * 24 * 90); //90 days

    
//Email:
    
    //It is highly recommended that authentication be required when enabling the 
    //email functionality in order to prevent spam!

    //The assumed domain of an authenticated user and email sender
    $assumedDomain = 'your.domain';  
    
    //Allow the credentials to be sent via email at the web form.
    $enableEmail = false;  
    
    //Set the username as the email sender (requires authentication)
    $enableSender = true; 
    
    //The above two settings result in the email appearing to have been sent by the 
    //authenticated user. For example, if your apache auth username is 'user', and 
    //the assumed domain is 'your.domain', the sender would be 'user@your.domain'.
    
    
//User interface misc.:

    //Logo location relative to the deploy directory
    $logo = "phpwpusher.png"; 
    
    //Site Title
    $title = 'PHPassword Pusher';
    
    //Enable display of the URL to the user.
    $displayURL = true;  
    
    //default minutes until the link expires
    $expirationTimeDefault = 15; 
    
    //default # of views before the link expires
    $expirationViewsDefault = 2; 
    
    //Warning displayed on credential creation form submission
    $submitWarning = 'Submitted credentials will expire and be erased according ' .
        'to the time or view limit, whichever occurs first.';
      
    //Critical warning displayed on every page and email.
    $criticalWarning = 'NEVER leave credentials where they can be easily accessed ' .
        'by others.'; 


?>
