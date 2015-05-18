<?php

/**
 * User Interface Functions
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */

/**
 * Print the document header, including title, logo, etc.
 *
 * @return string returnString
 */
function getHeader()
{
    include 'config.php';
    /** @noinspection PhpUndefinedVariableInspection */
    return
        '<!DOCTYPE html>
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

          .input-group-btn:last-child > .form-control {
            margin-left: -1px;
            width: auto;
          }

          .input-group {
            margin-bottom: 13px;
          }
        </style>

        <!-- jQuery -->
        <script src="jQuery/jQuery.js" charset="utf-8"></script>
        
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
            
        <script src="bootstrap/js/bootstrap.min.js" charset="utf-8">
        </script>
               
        <!-- Placeholder -->
        <script src="placeholder/Placeholder.min.js" charset="utf-8">
        </script>
        
        <script>
          Placeholders.init({
            live: true, //Apply to future and modified elements too
            hideOnFocus: true //Hide the placeholder when the element receives focus
          })
        </script>
        <script>
            $(function () {
                $(\'[data-toggle="tooltip"]\').tooltip()
            })
        </script>
      </head>
      <body>';
}

/** @noinspection PhpUndefinedClassInspection */

/** 
 * Print the document footer
 *
 * @return returnString
 */
function getFooter() 
{
    include 'config.php';
    /** @noinspection PhpUndefinedVariableInspection */
    return '<div class="alert alert-danger">' .
        $criticalWarning . '</div></body></html>'; 
}

/** @noinspection PhpUndefinedClassInspection */

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
        array('pw.php', translate('createNavLink')),
        array('about.php', translate('aboutNavLink'))
    );
    
    //Display logout nav if relevant
    /** @noinspection PhpUndefinedVariableInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    if($requireApacheAuth || $requireCASAuth) {
        array_push($pages, array('logout.php', translate('logoutNavLink')));
    }

    //First part of the navbar
    /** @noinspection PhpUndefinedVariableInspection */
    $returnString =  '<nav class="navbar navbar-default navbar-fixed-top">
                        <div class="container">
                            <div class="navbar-header">
                                <span class="navbar-brand">' . $title . '</span>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-nav">';

    //For each page in the pages array, determine whether the page is "active" 
    //(the current page) and add it to the navbar.
    for ($i = 0; $i < sizeof($pages); $i++) {
        $class = '';
        
        //Basename gets the filename listed in the REQUEST_URI
        if (substr(strrchr($_SERVER['PHP_SELF'], "/"), 1) == $pages[$i][0]) {
            $class = ' class="active"';
        }         
        //Set the finished link.                        
        $returnString .= '<li' . $class . '><a href="' . $pages[$i][0] . '">' . 
            $pages[$i][1] . '</a></li>';
    }
    
    //Finish off the returnString
    /** @noinspection PhpUndefinedVariableInspection */
    $returnString .= '      </ul>
                          </div>
                        </div>
                      </nav>
                      <div class="container">
                      <img class="img-responsive center-block" style="height:50px;"
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
    $returnString = '<div class="jumbotron"><h3 style="font-weight:bold;">' . translate('createLink') . '</h3>' .
        '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
    
     //Display creator username if email and authentication are configured.
    /** @noinspection PhpUndefinedVariableInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    if ($enableEmail && ($requireApacheAuth || $requireCASAuth)) {
        $returnString .= '<label class="control-label" for="destemail">' . translate('sender') . ': '; 
        if(isset($_SERVER['PHP_AUTH_NAME'])) {
            $returnString .= $_SERVER['PHP_AUTH_NAME'];
        } else {
            $returnString .= $_SERVER['PHP_AUTH_USER']; 
        }
        $returnString .= '</label>';
    }
    
    //Create the basic credential creation form
    /** @noinspection PhpUndefinedVariableInspection */
    /** @noinspection PhpUndefinedVariableInspection */
    $returnString .=
             '<div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true" data-container="body"
                  data-toggle="tooltip" data-placement="top" title="This is the secret you want to send to the other person."></span>
                  </span>' .
                  '<textarea class="form-control" placeholder="' . translate('secret') . '" name="cred" aria-describedby="cred" />' .
                  '</textarea>
              </div>
              <div class="input-group">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time" aria-hidden="true" data-container="body"
                        data-toggle="tooltip" data-placement="top"
                        title="The maximum amount of time for the secret to remain on the server before it is automatically deleted."></span>
                  </span>
                  <input class="form-control" type="text" placeholder="' .
                      $expirationTimeDefault . 
                      '" name="time" aria-label="time" />
                  <div class="input-group-btn">
                    <select class="form-control">
                      <option>' . translate('minutes') . '</option>
                      <option>' . translate('hours') . '</option>
                      <option>' . translate('days') . '</option>
                    </select>
                  </div>
                  
                </div>
              <div class="input-group">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true" data-container="body"
                        data-toggle="tooltip" data-placement="top"
                        title="The maximum number of times the link can be viewed before it is automatically deleted."></span>
                  </span>
                  <input class="form-control" type="text" ' . 'placeholder="' .
                      $expirationViewsDefault . 
                      '" name="views" />
                  <span class="input-group-addon">' . translate('views') . '</span>
                </div>';
              
    //Display field for destination email if enabled.
    if ($enableEmail) {  
        $returnString .=           
             '<div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-user" aria-hidden="true" data-container="body"
                        data-toggle="tooltip" data-placement="top"
                        title="The name of the person to email this link to."></span>
                  </span>
                  <input
                      class="form-control"
                      type="text" 
                      placeholder="' . translate('recipientNamePlaceholder') . '" 
                      name="destname" />
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-envelope" aria-hidden="true" data-container="body"
                        data-toggle="tooltip" data-placement="top"
                        title="The email address to send this link to."></span>
                  </span>
                  <input
                      class="form-control"
                      type="text" 
                      placeholder="' . translate('recipientEmailPlaceholder') . '" 
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
    $returnString = '<h2>' . translate('sharedCredential') . '</h2>' . 
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
    
    $returnString = '<div class="hero-unit"><h2>' . translate('giveLink') . '</h2>' .
      '<div class="pagination-centered"><div><code>' . $url . '</code></div>';

    /** @noinspection PhpToStringImplementationInspection */
    $returnString .= getZeroClipboard($url);

    /** @noinspection PhpUndefinedVariableInspection */
    $returnString .= '<br/><div class="pagination-centered"><p>' . $submitWarning . '</p>' .
        '<a href="' . $url . '&remove=1">' . 
        '<button class="btn btn-mini btn-danger">' . translate('deleteLink') . '</button></a></div>';
        
    $returnString .= '</div>';
    
    return $returnString;
}

/** @noinspection PhpUndefinedClassInspection */

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
                '<span id="precopy">' . translate('copyToClipboard') . '</span>' .
                '<span id="postcopy" style="display:none">' . 
                translate('copySuccess') . 
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

/** @noinspection PhpUndefinedClassInspection */


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

/** @noinspection PhpUndefinedClassInspection */


/** 
 * Print warning to page
 *
 * @param string $warning warningto print
 *
 * @return returnString
 */
function getWarning($warning) 
{
    return '<div class="alert alert-warning">' . $warning . '</div>';
}

/** @noinspection PhpUndefinedClassInspection */


/** 
 * Print errors to page
 *
 * @param string $error error to print
 *
 * @return returnString
 */
function getError($error) 
{
    return '<div class="alert alert-danger">' . $error. '</div>';
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
        if ($days > 1) {
            $timePhrase .= $days . ' ' . translate('days');
        } else {
            $timePhrase .= $days . ' ' . translate('day');
        }
    }
    
    //Determine if there are leftover hours and minutes
    if ($days > 0 && ($hours + $minutes) > 0) {
        $timePhrase .= ' + ';
    }
    
    //Determine hours
    if ($hours > 0) {
       
        if ($hours > 1) {
            $timePhrase .= $hours . ' ' . translate('hours');
        } else {
            $timePhrase .= $hours . ' ' . translate('hour');
        }
    }
    
    //Determine if there are leftover minutes
    if ($hours > 0 && $minutes > 0) {
        $timePhrase .= ' + ';
    }
    
    //Determine minutes
    if ($minutes > 0) {
        
        if ($minutes > 1) {
            $timePhrase .= $minutes . ' ' . translate('minutes');
        } else {
            $timePhrase .= $minutes . ' ' . translate('minute');
        }
    }
    
    return $timePhrase;
}

/**
 * Translate using the language files
 * @param $phrase
 *
 * @return $translatedPhrase
 */
function translate($phrase) {
    require 'config.php';
    /** @noinspection PhpUndefinedVariableInspection */
    require 'languages/' . $language . '.php';
    
    return ${$phrase};
}