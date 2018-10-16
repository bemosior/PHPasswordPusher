<?php 
/**
 * Mailer functionality
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */


/**
 * Mail the credentials to the recipient.
 *
 * @param string $url credential access URL
 * @param string $destEmail destination email
 * @param $destName
 * @param integer $expirationTime number of minutes until expiration
 * @param integer $expirationViews number of views until expiration
 * @return none
 */
function mailURL($url, $destEmail, $destName, $expirationTime, $expirationViews) 
{
    include 'config.php';
    $sender = 'phpw' . '@' . $assumedDomain;
    if (!empty($_SERVER['PHP_AUTH_USER']) && $enableSender == 1) {
        $sender = $_SERVER['PHP_AUTH_USER'] . '@' . $assumedDomain; 
    } 
    
    //Assemble the message
    $message = $destName . ",\r\n\r\n" . translate('emailWarn') . ' ' . $expirationTime . ' / ' .
        $expirationViews . ' ' . translate('views') . "\r\n" .$url . "\r\n\r\n" .  
        translate('criticalWarning') . "\r\n\r\n" . translate('emailSignature');
    
    $subject = translate('sentCredential') . ' ';
    
    //Set Signed Name if given
    if(isset($_SERVER['PHP_AUTH_NAME'])) {
        $message .= "\r\n" . $_SERVER['PHP_AUTH_NAME'];
        $subject .= $_SERVER['PHP_AUTH_NAME'];
    } else {
        $subject .= $sender;
    }
    

    
    $headers = 'From: ' . $sender  . "\r\n";
    mail(
        $destEmail, 
        $subject, 
        $message,
        $headers
    ) or die('Email send failed!');
    
    //Can be enabled to send a second email logging who sent a credential to whom
    //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' .
    //    $destEmail, '') or die('Email send failed!');

    /** @noinspection PhpToStringImplementationInspection */
    print getSuccess(translate('emailSent') . ' ' . $destEmail . '.');
}
