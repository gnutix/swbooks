[![Build Status](https://travis-ci.org/gnutix/swbooks.png?branch=dev)](https://travis-ci.org/gnutix/swbooks)

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
2. Clone the repository into a `swbooks` folder (`git clone git@github.com:gnutix/swbooks.git swbooks`)
3. Navigate to the newly created `swbooks` folder (`cd swbooks`)
4. Install [Composer](http://getcomposer.org/doc/00-intro.md#installation-nix) and run `composer.phar install`
5. Install [Bower](http://bower.io/#installing-bower) and run `bower install`
6. Access the application via [http://localhost/swbooks/web](http://localhost/swbooks/web)

Execute the tests
-----------------

1. Follow the "Project setup" chapter
2. Run `bin/phpunit`

Coding standards
----------------

1. Follow the "Project setup" chapter
2. Run `bin/console cs`
