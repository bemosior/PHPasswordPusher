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

        <link rel="stylesheet" type="text/css" href="fontello/css/fontello.css" />

        <!-- jQuery -->
        <script src="scripts/jquery-3.1.0.min.js" charset="utf-8"></script>

        <!-- Tether for tooltips -->
        <script src="tether/js/tether.min.js">
        </script>
        
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
            
        <script src="bootstrap/js/bootstrap.min.js" charset="utf-8">
        </script>

        <!-- Init tooltips -->
        <script>
          $(function () {
            $(\'[data-toggle="tooltip"]\').tooltip();
          });
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
    return '<div class="alert alert-danger">' .
        translate('criticalWarning') . '</div></div></body></html>';
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
        array('pw.php', translate('createNavLink')),
        array('about.php', translate('aboutNavLink'))
    );
    
    //Display logout nav if relevant
    if($requireApacheAuth || $requireCASAuth) {
        array_push($pages, array('logout.php', translate('logoutNavLink')));
    }

    //First part of the navbar
    $returnString =  '<nav class="navbar navbar-light navbar-fixed-top bg-faded">
                        <div class="container">
                            <div class="navbar-header">
                                <span class="navbar-brand">' . $title . '</span>
                            </div>
                                <ul class="nav navbar-nav">';

    //For each page in the pages array, determine whether the page is "active" 
    //(the current page) and add it to the navbar.
    for ($i = 0; $i < sizeof($pages); $i++) {
        $class = ' class="nav-item';
        
        //Basename gets the filename listed in the REQUEST_URI
        if (substr(strrchr($_SERVER['PHP_SELF'], "/"), 1) == $pages[$i][0]) {
            $class .= ' active';
        }         
        //Set the finished link.                        
        $returnString .= '<li' . $class . '"><a class="nav-link" href="' . $pages[$i][0] . '">' . 
            $pages[$i][1] . '</a></li>';
    }
    
    //Finish off the returnString
    $returnString .= '    </ul>
                        </div>
                      </nav>
                      <div class="container">
                      <img class="img-responsive center-block" style="height:50px;"
                          src="' . $logo . '" alt="logo"/>';
                      
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
    $returnString .=
             translate('introduction') . '<br />
              <br />
              <div class="input-group">
                <span class="input-group-addon icon-lock" data-toggle="tooltip" data-placement="top"
                  title="' . translate('secretTooltip') . '">
                </span>' .
                '<textarea class="form-control" placeholder="' . translate('secret') . '" name="cred">' .
                '</textarea>
              </div>
              <div class="input-group">
                  <span class="input-group-addon icon-clock" data-toggle="tooltip" data-placement="top"
                  title="' . translate('timeTooltip') . '">
                  </span>
                  <input class="form-control" type="text" placeholder="' .
                      $expirationTimeDefault . 
                      '" name="time" aria-label="time" />
                  <div class="input-group-btn">
                    <select class="form-control" name="units">
                      <option>' . translate('minutes') . '</option>
                      <option>' . translate('hours') . '</option>
                      <option>' . translate('days') . '</option>
                    </select>
                  </div>
                  
                </div>
              <div class="input-group">
                  <span class="input-group-addon icon-eye" data-toggle="tooltip" data-placement="top"
                  title="' . translate('viewsTooltip') . '">
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
                <span class="input-group-addon icon-user" data-toggle="tooltip" data-placement="top"
                  title="' . translate('recipientNameTooltip') . '">
                </span>
                  <input
                    class="form-control"
                    type="text" 
                    placeholder="' . translate('recipientNamePlaceholder') . '" 
                    name="destname" />
                <!-- </div> -->
              </div>
              <div class="input-group">
                <span class="input-group-addon icon-mail" data-toggle="tooltip" data-placement="top"
                  title="' . translate('recipientEmailTooltip') . '">
                </span>
                <input
                    class="form-control"
                    type="text" 
                    placeholder="' . translate('recipientEmailPlaceholder') . '" 
                    name="destemail" />
                <!-- </div> -->
              </div>
        ';
    }
    
    //Add the submit button
    $returnString .= '<input class="btn btn-primary btn-large" ' . 
        'type="submit" value="'.translate('submit').'" /></form></div>';
    
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
    $returnString = '<h4 style="font-weight:bold;margin-top:-40px">' .
        translate('sharedCredential') .
      '</h3>' .
      '<pre class="text-error" style="margin-top:30px;margin-bottom:30px">' . $cred . '</pre>';  
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
    
    $returnString = '<div class="jumbotron"><h3 style="font-weight:bold;">' . translate('giveLink') . '</h3>' .
      '<div style="text-align:center;margin-top:20px"><div><code id="final-url">' . $url . '</code></div>';

    $returnString .= getClipboardJs($url);
    $returnString .= '<br/><div style="margin-top:50px; margin-bottom: 20px;"><p>' . translate('submitWarning') . '</p>' .
        '<a href="' . $url . '&amp;remove=1" class="btn btn-mini btn-danger">' .
        translate('deleteLink') . '</a></div>';
        
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
function getClipboardJs($content)
{
    $returnString = '<script src="scripts/clipboard.min.js"></script>' .
      '<script>
        var cb = new Clipboard(\'#client_button\');
        cb.on(\'success\', function(e) {
          e.clearSelection();
          $(\'#client_button\').tooltip(\'show\');
          setTimeout(function(){$(\'#client_button\').tooltip(\'hide\');}, 1000);
        });
        $(\'#final-url\').click(function() {
          var $this = $(this);
          $this.select();
          var text = this, range, selection;

          if (document.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(text);
            range.select();
          } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
          }
        });
      </script>
      <div style="display: inline-block">
        <button id="client_button" class="btn btn-primary" style="margin-top: 10px"
          data-clipboard-target="#final-url" data-toggle="tooltip" data-animation="false"
          data-trigger="manual"
          title="' . translate('copySuccess') . '">' .
          translate('copyToClipboard') .
        '</button>
      </div>';
      
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
    return '<div class="alert alert-warning">' . $warning . '</div>';
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
    return '<div class="alert alert-danger">' . $error . '</div>';
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
    require 'languages/' . $language . '.php';
    
    return ${$phrase};
}
