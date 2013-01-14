<?php
/**
 * User Input Handling
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
    if (isset($_POST['cred'])) { 
        $arguments['cred'] = $_POST['cred']; 
        $arguments['func'] = 'post';
    }
    if (isset($_POST['minutes'])) {
        $arguments['minutes'] = $_POST['minutes'];
        $arguments['func'] = 'post';
    }
    if (isset($_POST['views'])) { 
        $arguments['views'] = $_POST['views'];
        $arguments['func'] = 'post';
    }
    if (isset($_POST['destemail'])) { 
        $arguments['destemail'] = $_POST['destemail'];
        $arguments['func'] = 'post';
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
            print getError('Please enter a valid credential!');
            return false;
        }
    }
    
    //Check Minutes
    if (isset($arguments['minutes'])) {
    
        //Set to the default value if empty
        if (empty($arguments['minutes'])) {
            $arguments['minutes'] = $expirationTimeDefault;
        }
        
        //Sanitize the input
        $arguments['minutes'] = sanitizeNumber($arguments['minutes']);
        if ($arguments['minutes'] == false) { 
            print getError(
                'Please enter a valid time limit (positive whole number)!'
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
            print getSuccess('No email address was entered, so no email has been sent.');
        } else {
            $arguments['destemail'] = sanitizeEmail($arguments['destemail']);
            if ($arguments['destemail'] == false) {
                print getWarning('Please enter a valid email address!');
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