<?php

/**
 * User Interface Functions
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */

/**
 * Print the document header, including title, logo, etc.
 *
 * @return string returnString
 */
function getHeader() 
{
    include 'config.php';
    return 
     '<!DOCTYPE HTML>
      <html lang="en">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>' . $title . '</title>
        
        <style type="text/css">
          body {
            padding-top: 60px;
            padding-bottom: 40px;
          }
        </style>
        
        <!-- Twitter Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        
        <link href="bootstrap/css/bootstrap-responsive.css" 
            rel="stylesheet">
            
        <script src="bootstrap/js/bootstrap.min.js" charset="utf-8">
        </script>
        
        <!-- jQuery -->
        <script src="jQuery/jQuery.js" charset="utf-8"></script>
               
        <!-- Placeholder -->
        <script src="placeholder/Placeholder.min.js" charset="utf-8">
        </script>
        
        <script>
          Placeholders.init({
            live: true, //Apply to future and modified elements too
            hideOnFocus: true //Hide the placeholder when the element receives focus
          })
        </script>
      </head>
      <body>';
}

/** 
 * Print the document footer
 *
 * @return returnString
 */
function getFooter() 
{
    include 'config.php';
    return '<div class="alert alert-error">' . 
        $criticalWarning . '</div></body></html>'; 
}

/**
 * Print the navbar
 *
 * @return returnString
 */
function getNavBar() 
{ 
    include 'config.php';
    
    //Define the pages
    $pages = array( 
        array('pw.php', 'Create'),
        array('about.php', 'About')
    );
                    
    //First part of the navbar
    $returnString =  '<div class="navbar navbar-fixed-top">
                        <div class="navbar-inner">
                          <div class="container" >
                            <div class="brand">' . $title . '</div>
                            <ul class="nav">';
                                  
    //For each page in the pages array, determine whether the page is "active" 
    //(the current page) and add it to the navbar.
    for ($i = 0; $i < sizeof($pages); $i++) {
        $class = '';
        
        //Basename gets the filename listed in the REQUEST_URI
        if (basename($_SERVER["REQUEST_URI"]) == $pages[$i][0]) {
            $class = ' class="active"';
        }         
        //Set the finished link.                        
        $returnString .= '<li' . $class . '><a href="' . $pages[$i][0] . '">' . 
            $pages[$i][1] . '</a></li>';
    }
    
    //Finish off the returnString
    $returnString .= '      </ul>
                          </div>
                        </div>
                      </div>
                      <div class="container">
                      <img style="height:50px; display:block; 
                          margin-left:auto; margin-right:auto;"
                          src="' . $logo . '" />';    
                      
    return $returnString;
}

/**
 * Print the credential creation form inputs
 *
 * @return string returnString
 */
function getFormElements() 
{
    include 'config.php';
    
    //Create basic credential form layout
    $returnString = '<div class="hero-unit"><h2>Create the link:</h2>' . 
        '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
    
     //Display creator username if email and authentication are configured.
    if ($enableEmail && $requireAuth) {  
        $returnString .= '<label class="control-label" for="destemail">Sender: ' . 
            $_SERVER['PHP_AUTH_USER'] . '</label>'; 
    }
    
    //Create the basic credential creation form
    $returnString .= 
             '<div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><i class="icon-lock"></i></span>' .
                  '<textarea rows="3" placeholder="Secret" name="cred" />' .
                  '</textarea>
                </div>
              </div>
    
              <div class="controls">
                <div class="input-prepend input-append">
                  <span class="add-on"><i class="icon-time"></i></span>
                  <input class="span1" type="text" placeholder="' . 
                      $expirationTimeDefault . 
                      '" name="time" />
                  <select name="units" style="width:90px; background-color:#eee;">
                      <option>minutes</option>
                      <option>hours</option>
                      <option>days</option>
                      </select>
                  
                </div>
              </div>
    
              <div class="controls">
                <div class="input-prepend input-append">
                  <span class="add-on"><i class="icon-eye-open"></i></span>
                  <input class="span1" type="text" ' . 'placeholder="' . 
                      $expirationViewsDefault . 
                      '" name="views" />
                  <span class="add-on">views</span>
                </div>
              </div>';
              
    //Display field for destination email if enabled.
    if ($enableEmail) {  
        $returnString .=           
             '<div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><i class="icon-envelope"></i></span>
                  <input 
                      type="text" 
                      placeholder="recipient@your.domain" 
                      name="destemail" />
                </div>
              </div>
        ';
    }
    
    //Add the submit button
    $returnString .= '<input class="btn btn-primary btn-large" ' . 
        'type="submit" value="Submit" /></div>';
    
    return $returnString;
}

/** 
 * Print the credential
 *
 * @param string $cred the unencrypted credential
 *
 * @return string returnString
 */ 
function getCred($cred) 
{
    $returnString = '<h2>The shared credential:</h2>' . 
        '<pre class="text-error">' . $cred . '</pre>';  
    return $returnString;
}

/**
 * Generates the credential URL page
 *
 * @param string $url URL used to access the credential
 *
 * @return string returnString
 */
function getURL($url) 
{
    include 'config.php';
    
    $returnString = '<div class="hero-unit"><h2>Here\'s the link:</h2>' .
      '<div class="pagination-centered"><div><code>' . $url . '</code></div>';
      
    $returnString .= getZeroClipboard($url);
    
    $returnString .= '<br/><div class="pagination-centered"><p>' . $submitWarning . '</p>' . 
        '<a href="' . $url . '&remove=1">' . 
        '<button class="btn btn-mini btn-danger">Delete Link</button></a></div>';
        
    $returnString .= '</div>';
    
    return $returnString;
}

/**
 * Generates the ZeroClipboard functionality
 *
 * @param string $content content to be copied to the clipboard
 *
 * @return returnString
 */
function getZeroClipboard($content)
{
    $returnString = '<script type="text/javascript" ' . 
            'src="ZeroClipboard/ZeroClipboard.js" ></script>
        <span style="display: inline-block;">
          <div id="d_clip_button">
            <button id="clip_button" class="btn btn-small btn-primary">' . 
                '<span id="precopy">Copy To Clipboard</span>' .
                '<span id="postcopy" style="display:none">' . 
                'Succesfully Copied!' . 
                '</span></button>
          </div>
        </span>
      </div>
    
      <script language="JavaScript" >
        window.onload = function(){
            var clip = new ZeroClipboard.Client();
            ZeroClipboard.setMoviePath(' . 
            '\'ZeroClipboard/ZeroClipboard.swf\');
              clip.setText( \'' . $content . '\' );
          clip.setHandCursor( true );
              clip.setCSSEffects( true );
          clip.addEventListener( \'onComplete\', function(client, text) {
                var button = document.getElementById(\'clip_button\');
                button.className = \'btn btn-small\';
                var clip1 = document.getElementById(\'precopy\');
                var clip2 = document.getElementById(\'postcopy\');
                clip1.style.display = \'none\';
                clip2.style.display = \'inline\';
              } );
              clip.glue( \'d_clip_button\' );
          }
      </script>';
      
    return $returnString;
}


/**
 * Print success message to page
 *
 * @param string $message message to print
 *
 * @return returnString
 */
function getSuccess($message) 
{
    return '<div class="alert alert-success">' . $message . '</div>';
}


/** 
 * Print warning to page
 *
 * @param string $warning warningto print
 *
 * @return returnString
 */
function getWarning($warning) 
{
    return '<div class="alert">' . $warning . '</div>';
}


/** 
 * Print errors to page
 *
 * @param string $error error to print
 *
 * @return returnString
 */
function getError($error) 
{
    return '<div class="alert alert-error">' . $error. '</div>';
}


/**
 * Calculate the expiration time 
 *
 * @param integer $minutes minutes to be converted 
 *
 * @return string $timePhrase human-readable time phrase
 */
function calcExpirationDisplay($minutes) 
{
    //The phrase that communicates a human-readable time breakdown
    $timePhrase = '';
    
    //Determine rough breakdown of time between days, hours, and minutes.
    $days = floor($minutes / 1440);
    $hours = floor(($minutes - $days * 1440) / 60);
    $minutes = $minutes - ($days * 1440) - ($hours * 60);
   
    //Determine days
    if ($days > 0) {
        $timePhrase .= "$days day";
        if ($days > 1) {
            $timePhrase .= 's';
        }
    }
    
    //Determine if there are leftover hours and minutes
    if ($days > 0 && ($hours + $minutes) > 0) {
        $timePhrase .= ' + ';
    }
    
    //Determine hours
    if ($hours > 0) {
        $timePhrase .= "$hours hour";
        if ($hours > 1) {
            $timePhrase .= 's';
        }
    }
    
    //Determine if there are leftover minutes
    if ($hours > 0 && $minutes > 0) {
        $timePhrase .= ' + ';
    }
    
    //Determine minutes
    if ($minutes > 0) {
        $timePhrase .= "$minutes minute";
        if ($minutes > 1) {
            $timePhrase .= 's';
        }
    }
    
    return $timePhrase;
}
?>