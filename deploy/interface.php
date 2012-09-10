<?php

//Print the document header, including title, logo, etc.
function PrintHeader() {
  require 'config.php';
  return '<!DOCTYPE html>
            <html lang="en">
			<head>
			  <meta charset="utf-8">
              <title>' . $title . '</title>
            </head>
            <body>
              <img src="' . $installation . '/' . $logoname . '">
              <h2>' . $title . '</h2>';
}

// Print the document footer
function PrintFooter() {
  require 'config.php';
  return '</body></html>';
}

//Print the credential
function PrintCred($cred) {
  print('<table border="1"><tr><td><pre>' . $cred . '</pre></td></tr></table>');   
}

//Prints the URL and the ZeroClipboard javascript
function PrintURL($url) {
  print('<pre>' . $url . '</pre>' .
  '<script type="text/javascript" src="ZeroClipboard/ZeroClipboard.js" ></script>
    <div id="d_clip_button"><button>Copy To Clipboard</button><div id="copyblock" style="display:none;">Text Copied!</div></div>
	 
	
	<script language="JavaScript" >
	  window.onload = function(){
         var clip = new ZeroClipboard.Client();
	     ZeroClipboard.setMoviePath( \'ZeroClipboard/ZeroClipboard.swf\');
         clip.setText( \'' . $url . '\' );
		 clip.setHandCursor( true );
         clip.setCSSEffects( true );
		 clip.addEventListener( \'onComplete\', function(client, text) {
           var div = document.getElementById(\'copyblock\');
           div.style.display = \'inline\';
         } );
         clip.glue( \'d_clip_button\' );
	   }
  </script>');
}

function PrintWarning($warning) {
  print('<p><font color="FF0000">' . $warning . '</font></p>');
}

//Print errors to page
function PrintError($error) {
  print('<p><font color="FF0000">' . $error. '</font></p>');
}

//Determine which elements to include before prompting the user
function GeneratePrompt() {
  require 'config.php';
  $prompt = array();
  

  return $prompt;
}

//Calculate the expiration time
function CalcTime($minutes) {
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