<?php
/**
 * User Input Handling
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
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
        $arguments['cred'] = sanitizeCred($arguments['cred']);
        if ($arguments['cred'] == false) {
            $arguments['func'] = 'none';
            print getError('Please enter the secret (whatever it may be)!');
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
            print getError(
                'Please enter a valid time limit (positive whole number)!'
            );
            return false;
        }
        
        //Apply unit conversion
        if (isset($arguments['units'])) {
            switch ($arguments['units']) {
                case "minutes":
                    //Do nothing, as time is already stored in minutes.
                    break;
                case "hours":
                    //Convert hours to minutes
                    $arguments['time'] = ($arguments['time'] * 60);
                    break;
                case "days":
                    //Convert days to minutes
                    $arguments['time'] = ($arguments['time'] * 60 * 24);
                    break;
            }                    
        }
        
        //Check against maximum lifetime
        if ($arguments['time'] > $credMaxLife) {
          print getError(
                'Please enter a time limit fewer than ' . 
                    calcExpirationDisplay($credMaxLife) . 
                    ' in the future!'
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
            print getError(
                'Please enter a valid view limit (positive whole number)!'
            );
            return false;
        }
    }
    
    //Check Email
    if (isset($arguments['destemail'])) {
        //Ignore if empty
        if (empty($arguments['destemail'])) {
            print getWarning('No email address was entered, so no email has been sent.');
        } else {
            $arguments['destemail'] = sanitizeEmail($arguments['destemail']);
            if ($arguments['destemail'] == false) {
                print getWarning('Please enter a valid email address!');
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
    if (strlen($email) > 50 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        $email = strip_tags($email);
        $email = mysql_real_escape_string($email);
        return $email;
    }
}

/**
 * Sanitize number entry
 *
 * @param integer $number number to be sanitized
 *
 * @return $number|boolean failure sanitized number or failure
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
 * Check and Sanitize the user's credentials.
 *
 * @param string $cred credential to be sanitized
 *
 * @return string $cred|boolean failure sanitized credential or failure
 */
function sanitizeCred($cred) 
{
    if (empty($cred)) {
        return false;
    } else {
        $cred = htmlspecialchars($cred);
        return $cred;
    }
}

?>