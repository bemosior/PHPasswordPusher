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

    //IP Whitelist for creating credentials
    //Whitelist is an array of CIDR notation IP addresses
    $checkCreatorIpWhitelist = false;
    $creatorIpWhitelist = array(
        "10.0.0.0/24"
    );
    
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
    
    // Default sender address. This will be overriden if enableSender = true and authentication is enabled
	$fromEmail = 'noreply@example.com';

    // Display name for given email fromEmail address
    $fromName  = 'PHPasswordPusher';

    // Default signature to place at end of email (html format!)
    // Leave empty if no signature is required/wanted
    // If enableSender and auth is enabled, this will be ignored
    $signature = '<b>Company Name</b>';

    // If enabled, the PHPMailer library is used
    $PHPMailer = false;

    // Path to src folder for PHPMailer - without trailing /
    $PHPMailerPath = '/opt/PHPMailer/src';

    // Use SMTP. If false, mail() will be used
    $PHPMailerSmtp = true;

    // SMTP Host
    $PHPMailerHost = "smtp.example.com";

    // SMTP Port
    $PHPMailerPort = 25;

    // StartTLS/SSL/TLS encryption
    $PHPMailerSecure = 'StartTLS';

    //User to authentication against SMTP server. Leave blank if sending as anonymous
    $PHPMailerUser = '';

    // Password for PHPMailerUser
    $PHPMailerPassword = '';
	
//User interface misc.:

    //Logo location relative to the deploy directory
    $logo = "phpwpusher.png"; 
    
    //Site Title
    $title = 'PHPassword Pusher';
    
    //Site Language (corresponds to $language.php)
    $language = 'en'; 
    
    //Local language to be used by moment javascript. Normally set to same as $language
    //but can in cases be different where there are dialects.
    //See https://momentjs.com/ for language options
    $language_moment = 'en';
	
    //Enable display of the URL to the user.
    $displayURL = true;  
    
    //default minutes until the link expires
    $expirationTimeDefault = 30; 
    
    //default # of views before the link expires
    $expirationViewsDefault = 2; 

    // If true, information on when the link expires will be visible when viewing the secret.
    $showExpiryInfo = true;
