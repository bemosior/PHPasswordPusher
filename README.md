PHPasswordPusher
================

PHPasswordPusher is a PHP port of the PasswordPusher project, which provides a
more secure method for sharing sensitive information (like passwords) with 
others. It operates on the principal that using a soon-to-be-expiring link to
retrieve sensitive information is better than having the sensitive 
information persist in email, chat, etc...

A demo is hosted at [bmosior.com](https://bmosior.com/phpw/pw.php)


## Overview
A user will enter the sensitive information (password, etc.) into the link form,
set a view and time limit, and receive a link. That link is 
communicated to the intended recipient, who then can retrieve the sensitive
information until the view or time limits are breached.

## Feature Set
* Secure Password/Credential Sharing
* Emailing Features
* Language Translation
* Deletion Link
* CAS (via phpCAS) and Apache Authentication Support

## Environment
PHPasswordPusher has so far been tested with the following environment:

* Linux (RHEL 5 and CentOS 5, though other distributions should work.)
* Apache
* MySQL 5.1.6+
* PHP 5.3+
* Central Authentication Service (CAS) 3.5.1 (optional)

## Installation
1. Set up the environment. You will need to install these packages: mysql-server, httpd (apache2), php, php-mcrypt,php-mysql, and php-xml.
2. Change configuration options in ./pwpusher_private/config.php   
3. Change configuration options in ./install.php. Remember to remove the credentials in this file when you're done with step 4!
4. From the command-line interface, run `php install.php` 
5. Copy the pwpusher_private and pwpusher_public directories to the same NON-PUBLIC directory of your choice (for instance, /var/www, but not inside public_html).
6. Configure Apache (customize the below sample as noted). If you care about security enough to use this project, you should definitely be using SSL and redirecting users requests from non-secure ports (for instance 80, in the default configuration) to whatever port is SSL-enabled (typically 443). The Apache documentation will help here: http://httpd.apache.org/docs/2.2/ssl/ssl_faq.html 

```         
##### PHPasswordPusher #####

### Change "/youralias/" and "/your/installation/dir/" to fit your installation:
Alias /youralias/ /your/installation/dir/pwpusher_public/

### Change "/your/installation/dir/" to fit your installation:
<Directory /your/installation/dir/pwpusher_public/>

    #If you use the email functionality, you should definitely enable authentication of some sort.
    #PHPassword Pusher supports both Apache auth (below) and CAS authentication (see config.ini for details)
    
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

## Languages
Language functionality has been added. The pwpusher_private/language/ files may be modified, 
and new languages can be added. Simply follow the examples already there and update the config.php.

## Other Projects Utilized
* PasswordPusher: https://github.com/pglombardo/PasswordPusher
* ZeroClipboard: https://github.com/jonrohan/ZeroClipboard
* Placeholder.js: https://github.com/jamesallardice/Placeholders.js
* Twitter Bootstrap: http://twitter.github.com/bootstrap
* phpCAS: https://wiki.jasig.org/display/CASC/phpCAS

