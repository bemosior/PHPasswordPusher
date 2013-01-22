PHPasswordPusher
================

PHPasswordPusher is a PHP port of the PasswordPusher project, which provides a
more secure method for sharing sensitive information (like passwords) with 
others. It operates on the principal that using a soon-to-be-expiring link to
retrieve sensitive information is better than having the sensitive 
information persist in email, chat, etc...


## Overview
A user will enter the sensitive information (password, etc.) into the link form,
set a view and time limit, and receive a link. That link is 
communicated to the intended recipient, who then can retrieve the sensitive
information until the view or time limits are breached.

## Environment
PHPasswordPusher has so far been tested with the following environment:

* Linux (RHEL 5 and CentOS 5, though other distributions should work.)
* Apache
* MySQL
* PHP 5.3

## Installation
1. Set up the environment. You will need these packages: mysqld, httpd (apache2), php53, php53-mcrypt, php53-pdo, php53-mysql, and uuid-php.
2. Change configuration options in ./pwpusher_private/config.php   
3. Change configuration options in ./install.php. Remember to remove the credentials in this file when you're done with steps 2 and 3!
4. From the command-line interface, run `php install.php` 
5. Copy the pwpusher_private and pwpusher_public directories to the same NON-PUBLIC directory of your choice (for instance, /var/www, but not inside public_html).
6. Configure Apache (customize the below sample as noted). If you care about security enough to use this project, you should definitely be using SSL and redirecting users requests from non-secure ports (for instance 80, in the default configuration) to whatever port is SSL-enabled (typically 443). The Apache documentation will help here: http://httpd.apache.org/docs/2.2/ssl/ssl_faq.html 

```         
##### PHPasswordPusher #####

### Change "/youralias/" and "/your/installation/dir/" to fit your installation:
Alias /youralias/ /your/installation/dir/pwpusher_public/

### Change "/your/installation/dir/" to fit your installation:
<Directory /your/installation/dir/pwpusher_public/>

    #If you use the email functionality, you should definitely enable authentication:
    #AuthName "Your login message."
    #AuthType Basic
    #AuthUserFile /your/.htpasswd
    #require valid-user

    AllowOverride None
    Order allow,deny
    Allow from all      
    DirectoryIndex pw.php  
</Directory>

### Change "/your/installation/dir/" to fit your installation:
<Directory /your/installation/dir/pwpusher_private/>
    AllowOverride None
    Order deny,allow
    Deny from all
</Directory>
```
7. Test your installation by navigating to http(s)://yourwebserver/youralias/
8. Celebrate victory.

## Other Projects Utilized
* PasswordPusher: https://github.com/pglombardo/PasswordPusher
* ZeroClipboard: https://github.com/jonrohan/ZeroClipboard
* Placeholder.js: https://github.com/jamesallardice/Placeholders.js
* Twitter Bootstrap: http://twitter.github.com/bootstrap

