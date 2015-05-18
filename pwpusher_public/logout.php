<?php
/**
 * The "logout" page
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 */
 
require '../pwpusher_private/interface.php';
require '../pwpusher_private/config.php';
require '../pwpusher_private/CAS/CAS.php';

//Print the header
print getHeader();

//Print the navbar
/** @noinspection PhpToStringImplementationInspection */
print getNavBar();

//Print the logout page.
print('<div class="hero-unit"><h2>Logout</h2>');
if($requireApacheAuth) {
    /** @noinspection PhpToStringImplementationInspection */
    print(getError(translate('apacheLogout')));
} elseif ($requireCASAuth) {
  phpCAS::client(SAML_VERSION_1_1, $casHost, $casPort, $casContext);
  phpCAS::Logout();
}
print('</div>');
        
//Print the footer
/** @noinspection PhpToStringImplementationInspection */
print getFooter();