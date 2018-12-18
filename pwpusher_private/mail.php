<?php 
/**
 * Mailer functionality
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */

// Setup for PHPMailer. Even if PHPMailer is not enabled or present it will not generate errors
use PHPMailer\PHPMailer\PHPMailer;  // Inclusion of namespace will not cause any issue even if PHPMailer is not used
use PHPMailer\PHPMailer\Exception;

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
    $sender = $fromEmail; 
    if (!empty($_SERVER['PHP_AUTH_USER']) && $enableSender == 1) {
        $sender = $_SERVER['PHP_AUTH_USER'] . '@' . $assumedDomain; 
    } 
    
    //Assemble the message
    $message = $destName . ",<br><br>" . translate('emailWarn') . ' ' . $expirationTime . ' / ' .
        $expirationViews . ' ' . translate('views') . "<br><a href='" .$url . "'>$url</a><br><br>" .  
        translate('criticalWarning') . "<br><br>" . translate('emailSignature');
    
    $subject = translate('sentCredential') . ' ';
    
    //Set Signed Name if given
    if(isset($_SERVER['PHP_AUTH_NAME'])) {
        $message .= "<br>" . $_SERVER['PHP_AUTH_NAME'];
        $subject .= $_SERVER['PHP_AUTH_NAME'];
    } else {
        $subject .= $sender;
        $message .= '<br>'.$signature;
    }

    // Add Content-Type/charset header to support non-english characters in emails 
    $headers = "Content-Type:text/html; charset=UTF-8\r\n";
    $headers.= 'From: ' .$fromName .' <'. $fromEmail  . ">\r\n";

    if ( $PHPMailer ) {
        require_once($PHPMailerPath .'/Exception.php'); /* Exception class. */
        require_once($PHPMailerPath .'/PHPMailer.php'); /* The main PHPMailer class. */
            
        if ( $PHPMailerSmtp ) {
            require_once($PHPMailerPath .'/SMTP.php');  /* SMTP class, needed if you want to use SMTP. */
        }
            
        $phpmail = new PHPMailer(false);
        $phpmail->CharSet = 'UTF-8';
        $phpmail->setFrom($sender, $fromName);
        $phpmail->addReplyTo($sender, $fromName);
            
        // Define SMTP parameters if enabled 
        if ( $PHPMailerSmtp ) {
                
            $phpmail->isSMTP(); 
            $phpmail->Host = $PHPMailerHost;
            $phpmail->Port = $PHPMailerPort;
            $phpmail->SMTPSecure = $PHPMailerSecure;
                
            // Handle authentication for SMTP if enabled
            if ( !empty($PHPMailerUser) ) {
                $phpmail->SMTPAuth = true;
                $phpmail->Username = $PHPMailerUser;
                $phpmail->Password = $PHPMailerPassword;
            }
        }
            
        $phpmail->addAddress($destEmail);
        $phpmail->Subject = $subject;
        $phpmail->msgHtml($message);
            
        $phpmail->isHtml(true);    // use htmlmail if enabled
        if ( ! $phpmail->send() ) {
            // TODO Log error message $phpmail->ErrorInfo;
            die('Email send failed!');                    
        }

    } else {
        mail(
            $destEmail, 
            $subject, 
            $message,
            $headers
        ) or die('Email send failed!');
    }
    
    //Can be enabled to send a second email logging who sent a credential to whom
    //mail('loggingemailhere', 'PHPassPush: ' . $sender . ' sent a credential to ' .
    //    $destEmail, '') or die('Email send failed!');

    /** @noinspection PhpToStringImplementationInspection */
    print getSuccess(translate('emailSent') . ' ' . $destEmail . '.');
}
