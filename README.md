PHPasswordPusher
================

PHPasswordPusher is a PHP port of the PasswordPusher project, which provides a
more secure method for sharing sensitive information (like passwords) with 
others. It operates on the principal that using a soon-to-be-expiring link to
retrieve sensitive information is better than having the sensitive 
information persist in email, chat, etc...

A user will enter the sensitive information (password, etc.) into the pwlink 
script, set a view and time limit, and receive a link. That link is 
communicated to the intended recipient, who then can retrieve the sensitive
information until the view or time limits are breached.

This application is still in its infancy, but it can be considered 
functionally complete. I plan to have an official release by October 2012 
that should include a number of enhancements, so check back soon!


// Credits //

* PasswordPusher: https://github.com/pglombardo/PasswordPusher
* ZeroClipboard: https://github.com/jonrohan/ZeroClipboard