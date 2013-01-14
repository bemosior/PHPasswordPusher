<?php 
/**
 * Mailer functionality
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
 
/**
 * Mail the credentials to the recipient.
 *
 * @param string  $url             credential access URL
 * @param string  $destEmail       destination email
 * @param integer $expirationTime  number of minutes until expiration
 * @param integer $expirationViews number of views until expiration
 * 
 * @return none
 */
function mailURL($url, $destEmail, $expirationTime, $expirationViews) 
{
    include 'config.php';
    
    $sender = 'phpw' . '@' . $assumedDomain; 
    if (!empty($_SERVER['PHP_AUTH_USER']) && $enableSender == 1) { 
        $sender = $_SERVER['PHP_AUTH_USER'] . '@' . $assumedDomain; 
    } 
    
    $message = $url . "\r\n\n" . 'This link contains sensitive information ' .
        'and will be inaccessible after ' . $expirationTime . ' OR ' . 
        $expirationViews . ' views, whichever occurs first.' . "\r\n" . 
        $criticalWarning;
    
    $headers = 'From: ' . $sender  . "\r\n";
    mail(
        $destEmail, 
        $sender . ' sent you a credential. ', 
        $message,
        $headers
    ) or die('Email send failed!');
    
    //Can be enabled to send a second email logging who sent a credential to whom
    //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' .
    //    $destEmail, '') or die('Email send failed!');
    
    print getSuccess("Email sent to $destEmail.");
}


?>