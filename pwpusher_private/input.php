<?php
/**
 * User Input Handling
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
 
/**
 * Grab arguments from POST
 *
 * @return array $arguments
 */
function getArguments() 
{
    $arguments = Array();
    if (isset($_GET['id'])) { 
        $arguments['id'] = $_GET['id'];
        $arguments['func'] = 'get';
    }
    if (isset($_GET['remove'])) { 
        $arguments['remove'] = $_GET['remove'];
        $arguments['func'] = 'remove';
    }
    
    if (isset($_POST['cred'])) { 
        $arguments['cred'] = $_POST['cred']; 
        $arguments['func'] = 'post';
    }
    if (isset($_POST['time'])) {
        $arguments['time'] = $_POST['time'];
    }
    if (isset($_POST['units'])) {
        $arguments['units'] = $_POST['units'];
    }
    if (isset($_POST['views'])) { 
        $arguments['views'] = $_POST['views'];
    }
    if (isset($_POST['destemail'])) { 
        $arguments['destemail'] = $_POST['destemail'];
    }
	if (isset($_POST['destname'])) { 
        $arguments['destname'] = $_POST['destname'];
    }
	
    if (!$arguments) {
        $arguments['func'] = 'none';
    }
    return $arguments;
}

/**
 * Sanitize all the inputs and determine their validity.
 *
 * @param array $arguments user input
 *
 * @return array arguments|boolean failure
 */
function checkInput($arguments) 
{
    include 'config.php';
       
    //Check the credential
    if (isset($arguments['cred'])) {
        $arguments['cred'] = sanitizeText($arguments['cred']);
        if ($arguments['cred'] == false) {
            $arguments['func'] = 'none';
            /** @noinspection PhpToStringImplementationInspection */
            print getError(translate('enterSecret'));
            return false;
        }
    }
    
    //Check Minutes
    if (isset($arguments['time'])) {
    
        //Set to the default value if empty
        if (empty($arguments['time'])) {
            $arguments['time'] = $expirationTimeDefault;
        }
        
        //Sanitize the input
        $arguments['time'] = sanitizeNumber($arguments['time']);
        if ($arguments['time'] == false) {
            /** @noinspection PhpToStringImplementationInspection */
            print getError(translate('enterTime'));
            return false;
        }
        
        //Apply unit conversion
        if (isset($arguments['units'])) {
            switch ($arguments['units']) {
                case translate('minutes'):
                    //Do nothing, as time is already stored in minutes.
                    break;
                case translate('hours'):
                    //Convert hours to minutes
                    $arguments['time'] = ($arguments['time'] * 60);
                    break;
                case translate('days'):
                    //Convert days to minutes
                    $arguments['time'] = ($arguments['time'] * 60 * 24);
                    break;
            }                    
        }
        
        //Check against maximum lifetime
        if ($arguments['time'] > $credMaxLife) {
            /** @noinspection PhpToStringImplementationInspection */
            print getError(
                translate('validTimeLimit') . ' ' .
                calcExpirationDisplay($credMaxLife)
            );
          return false;
        }
        
        
        
    }
    
    //Check Views
    if (isset($arguments['views'])) {
    
        //Set to the default value if empty
        if (empty($arguments['views'])) {
            $arguments['views'] = $expirationViewsDefault;
        }
        
        //Sanitize the input
        $arguments['views'] = sanitizeNumber($arguments['views']);
        if ($arguments['views'] == false) {
            /** @noinspection PhpToStringImplementationInspection */
            print getError(translate('validViewLimit'));
            return false;
        }
    }
    
    //Check Email
    if (isset($arguments['destemail'])) {
        //Ignore if empty
        if (empty($arguments['destemail'])) {
            /** @noinspection PhpToStringImplementationInspection */
            print getWarning(translate('noEmail'));
        } else {
		
		    //Check to see if a name is included
			if (empty($arguments['destname'])) {
                /** @noinspection PhpToStringImplementationInspection */
                print getWarning(translate('validName'));
				return false;
            } else {
                $arguments['destname'] = sanitizeText($arguments['destname']);
                if ($arguments['destname'] == false) {
                    /** @noinspection PhpToStringImplementationInspection */
                    print getWarning(translate('validName'));
                    return false;
                }
            }
		
            $arguments['destemail'] = sanitizeEmail($arguments['destemail']);
            if ($arguments['destemail'] == false) {
                /** @noinspection PhpToStringImplementationInspection */
                print getWarning(translate('validEmail'));
                return false;
            }
        }
    }
    return $arguments;
}

/**
 * Check and Sanitize the user's email.
 *
 * @param string $email email address to be sanitized
 *
 * @return string $email|boolean failure sanitized email or failure
 */
function sanitizeEmail($email) 
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        $email = strip_tags($email);
        //$email = mysql_real_escape_string($email);  // function not valid on php7. However, no need to escape as it has already been sanatized and validated
        return $email;
    }
}

/**
 * Sanitize number entry
 *
 * @param integer $number number to be sanitized
 * @return bool|int $number|boolean failure sanitized number or failure
 */
function sanitizeNumber($number) 
{
    if (!filter_var($number, FILTER_VALIDATE_INT) || $number < 0) {
        return false;
    } else {
        return $number;
    }
}

/**
 * Check and Sanitize user text.
 *
 * @param string $text credential to be sanitized
 *
 * @return string $text|boolean failure sanitized credential or failure
 */
function sanitizeText($text) 
{
    if (empty($text)) {
        return false;
    } else {
        $text = htmlspecialchars($text);
        return $text;
    }
}
