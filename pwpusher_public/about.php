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
/*print('<div class="jumbotron"><h3 style="font-weight:bold;">What is PHPasswordPusher?</h3>
        PHPasswordPusher is a PHP port of the PasswordPusher project, which provides
        a more secure method for sharing sensitive information like passwords) with 
        others. It operates on the principle that using a soon-to-be-expiring link 
        to retrieve sensitive information is better than having the sensitive 
        information persist in email, chat, etc...
        <br/><br/>
        A user will enter the sensitive information (password, etc.) into the pwlink
        script, set a view and time limit, and receive a link. That link is 
        communicated to the intended recipient, who then can retrieve the sensitive 
        information until the view or time limits are breached.
        <br/><br/>
        For more information, take a look at the 
        <a target="_blank" href="https://github.com/bemosior/PHPasswordPusher">
        GitHub page</a>.</div>');
*/
print('<div class="jumbotron"><h3 style="font-weight:bold;">'.translate('aboutTitle').'</h3>'. translate('aboutText').'</div>');
//Print the footer
/** @noinspection PhpToStringImplementationInspection */
print getFooter();
