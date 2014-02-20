[![Build Status](https://travis-ci.org/gnutix/swbooks.png?branch=dev)](https://travis-ci.org/gnutix/swbooks)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e275cb12-5729-4490-baad-a3898fd71ff9/mini.png)](https://insight.sensiolabs.com/projects/e275cb12-5729-4490-baad-a3898fd71ff9)

Star Wars Library
=================

This single web page application allows me to list my Star Wars books (with some books information), featuring the
number of readings I've done and copies I own for each books releases.

Application
-----------

This application makes heavy usage of Symfony2's components to create a POO model representing a book's library (loaded
from an XML or YAML source file).

Project dependencies
--------------------

The project is meant to run on any webserver with PHP >= 5.3.0. No database is needed.
Node.js is needed for frontend dependencies management (using Twitter Bower).

Project setup
-------------

First, ensure you have installed [git](http://git-scm.com/book/en/Getting-Started-Installing-Git#Installing-on-Linux)
and a local webserver. Then:

1. Navigate to your local webserver root folder
2. Clone the repository into a `gnutix/swbooks` folder (`mkdir -p gnutix && git clone git@github.com:gnutix/swbooks.git gnutix/swbooks`)
3. Navigate to the newly created `gnutix/swbooks` folder (`cd gnutix/swbooks`)
4. Install [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) and run `composer.phar install`
5. Install [Bower](http://bower.io/#installing-bower) and run `bower install`
6. Access the application via [http://localhost/gnutix/swbooks/web/app_dev.php/](http://localhost/gnutix/swbooks/web/app_dev.php/)

Setup a VirtualHost in Apache2
------------------------------

Create a new file for your virtual host (for example in `/etc/apache2/sites-available/gnutix-swbooks`) with the following content (assuming `/var/www/sites/` is your webserver root folder) :

```
<VirtualHost *:80>
    ServerName "swbooks.lo"
    DocumentRoot "/var/www/sites/gnutix/swbooks/web"

    <Directory "/var/www/sites/gnutix/swbooks/web">
        DirectoryIndex app.php
        Options -Indexes FollowSymLinks SymLinksifOwnerMatch
        AllowOverride All
        Allow from All
    </Directory>
</VirtualHost>
```

Then, add the following line to your `/etc/hosts` file : `127.0.0.1 swbooks.lo`. Finally, restart Apache (`sudo apachectl -k restart`).

You should be able to access the application with the following URL: [http://swbooks.lo/app_dev.php/](http://swbooks.lo/app_dev.php/).

Execute the tests
-----------------

1. Follow the "Project setup" chapter
2. Run `bin/phpunit`

Coding standards
----------------

1. Follow the "Project setup" chapter
2. Run `bin/console cs`
