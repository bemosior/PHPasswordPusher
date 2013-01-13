<?php
require 'includes/config.php'; 
require 'includes/database.php';
require 'includes/mail.php';
require 'includes/encryption.php';
require 'includes/input.php';
require 'includes/interface.php';

print PrintHeader();

print getNavBar();

print('<div class="hero-unit"><h2>What is PHPasswordPusher?</h2>
        PHPasswordPusher is a PHP port of the PasswordPusher project, which provides a more secure method for sharing sensitive information (like passwords) with others. 
        It operates on the principal that using a soon-to-be-expiring link to retrieve sensitive information is better than having the sensitive information persist in 
        email, chat, etc...
        <br/><br/>
        A user will enter the sensitive information (password, etc.) into the pwlink script, set a view and time limit, and receive a link. That link is communicated to 
        the intended recipient, who then can retrieve the sensitive information until the view or time limits are breached.
        <br/><br/>
        For more information, take a look at the <a target="_blank" href="https://github.com/bemosior/PHPasswordPusher">GitHub page</a>.</div>');
print PrintFooter();
?>