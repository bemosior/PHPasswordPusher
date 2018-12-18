<?php
/**
 * The "about" page
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
require '../pwpusher_private/config.php'; 
require '../pwpusher_private/interface.php';

//Print the header
print getHeader();

//Print the navbar
/** @noinspection PhpToStringImplementationInspection */
print getNavBar();

//Print the about page.
print('<div class="jumbotron"><h3 style="font-weight:bold;">'.translate('aboutTitle').'</h3>'. translate('aboutText').'</div>');

//Print the footer
/** @noinspection PhpToStringImplementationInspection */
print getFooter();
