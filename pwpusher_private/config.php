<?php
/**
 * Configuration Options
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
//MySQL and Apache configuration:
    //Change this if the host is on a different server
    $host = 'localhost';

    //The MySQL database name.
    $dbname = 'phpwpush';

    //The MySQL table name. ONLY change this if necessary!
    $tblname = "phpasspush";
    
    //The MySQL user
    $dbuser = 'phpw';  
    
    //The MySQL user's password. Change this value to something secure!
    $dbpass = 'phpass!3#.';
        
//Encryption and security:
    //The encryption key. It must be of length 16, 24, or 32 in order to use AES-128, AES-192, or AES-256, respectively.
    //If the key is not of the proper length, the application will fail to run.
    $key = 'changethiseddnc7o6gmhlz6df48z14z';
    
    //The salt for the link hash. Change it to something else secret.
    $salt = 'change this salt please!'; 
    
    //Require Apache user authentication.
    $requireApacheAuth = false;  
    
    //Require CAS user authentication. By default anyone can retrieve.
    $requireCASAuth = false;  
    $casHost = 'cas.example.com';
    $casContext = '/cas';
    $casPort = 443;
    $casServerCaCertPath = '/physical/path/to/cachain.pem'; //Contains the Certificate Authority (issuer) certificate.
    $casSamlNameAttribute = 'full_name'; //A SAML attribute that contains the full name of the user.
    
    //Protect credential retrieval as well (if set to false and using authentication, 
    //only authenticated users can create credentials, but anyone can retrieve).
    $protectRetrieve = true;
    
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
    
    //Site Language (corresponds to $language.php)
    $language = 'en'; 
    
    //Enable display of the URL to the user.
    $displayURL = true;  
    
    //default minutes until the link expires
    $expirationTimeDefault = 30; 
    
    //default # of views before the link expires
    $expirationViewsDefault = 2; 
