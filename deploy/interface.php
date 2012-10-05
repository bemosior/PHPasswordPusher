<?php

//Print the document header, including title, logo, etc.
function PrintHeader() {
  require 'config.php';
  return '<!DOCTYPE HTML>
            <html lang="en">
            <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <meta name="description" content="">
              <meta name="author" content="">
              <title>' . $title . '</title>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
              <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
              <style type="text/css">
                body {
                  padding-top: 60px;
                  padding-bottom: 40px;
                }
              </style>
              <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
            </head>
            <body>
              <script src="http://code.jquery.com/jquery-latest.js"></script>
              <script src="bootstrap/js/bootstrap.min.js"></script>
              <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                  <div class="container" >
                    <!-- <img style="height:75px; display:block; position:absolute; left:0px; top:45px;" src="' . $installation . '/' . $logoname . '" /> -->
                    <a class="brand" href="#">PHPasswordPusher</a>
                    <ul class="nav">
                      <li class="active"><a href="pw.php">Create</a></li>
                      <li><a href="#about">About</a></li>
                      <li><a href="#contact">Contact</a></li>
                    </ul>
                    
                  </div>
                </div>
              </div>
              <div class="container">
               
                ';
}

// Print the document footer
function PrintFooter($warning) {
  require 'config.php';
  return '<div class="alert alert-error">NEVER leave credentials where they can be easily accessed by others.</div></body></html>'; 
}

//Print the credential
function PrintCred($cred) {
  print('<h2>The shared credential:</h2><div class="pagination-centered"><pre class="text-error">' . $cred . '</pre></div>');   
}

//Prints the URL and the ZeroClipboard javascript
function PrintURL($url) {
  print('<div class="hero-unit"><h2>Here\'s your URL:</h2>' .
    '<div class="pagination-centered"><div><code>' . $url . '</code></div>
      <script type="text/javascript" src="ZeroClipboard/ZeroClipboard.js" ></script>
      <span style="display: inline-block;">
        <div id="d_clip_button">
          <button id="clip_button" class="btn btn-primary"><span id="precopy">Copy To Clipboard</span><span id="postcopy" style="display:none">Succesfully Copied!</span></button>
          <!-- <div id="copyblock" style="display:none;"><span class="alert alert-success">Text Copied!</span></div> -->
        </div>
      </span>
    </div>

  
    <script language="JavaScript" >
      window.onload = function(){
           var clip = new ZeroClipboard.Client();
         ZeroClipboard.setMoviePath( \'ZeroClipboard/ZeroClipboard.swf\');
           clip.setText( \'' . $url . '\' );
       clip.setHandCursor( true );
           clip.setCSSEffects( true );
       clip.addEventListener( \'onComplete\', function(client, text) {
             var button = document.getElementById(\'clip_button\');
             button.className = \'btn\';
             var clip1 = document.getElementById(\'precopy\');
             var clip2 = document.getElementById(\'postcopy\');
             clip1.style.display = \'none\';
             clip2.style.display = \'inline\';
           } );
           clip.glue( \'d_clip_button\' );
       }
    </script>
    </div>');
}

function PrintWarning($warning) {
  print('<div class="alert">' . $warning . '</div>');
}

//Print errors to page
function PrintError($error) {
  print('<div class="hero-unit"><span class="alert alert-error">' . $error. '</span></div>');
}

//Determine which elements to include before prompting the user
function GeneratePrompt() {
  require 'config.php';
  $prompt = array();
  return $prompt;
}

//Calculate the expiration time
function CalcHRTime($minutes) {
  $d = floor ($minutes / 1440);
  $h = floor (($minutes - $d * 1440) / 60);
  $m = $minutes - ($d * 1440) - ($h * 60);

  $HRTime = '';
  if ($d > 0) {
    $HRTime .= "$d day";
    if($d > 1) {
      $HRTime .= 's';
    }
  }
  if ($d > 0 && ($h + $m) > 0) {
    $HRTime .= ' + ';
  }
  if ($h > 0) {
    $HRTime .= "$h hour";
    if($h > 1) {
      $HRTime .= 's';
    }
  }
  if ($h > 0 && $m > 0) {
    $HRTime .= ' + ';
  }
  if ($m > 0) {
    $HRTime .= "$m minute";
    if($m > 1) {
      $HRTime .= 's';
    }
  }
  return $HRTime;
}
?>