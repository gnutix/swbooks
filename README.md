[![Build Status](https://travis-ci.org/gnutix/swbooks.png?branch=dev)](https://travis-ci.org/gnutix/swbooks)

Star Wars Library
=================

This single web page application allows me to list my Star Wars books (with some books information), featuring the
number of readings I've done and copies I own for each books releases.

Application
===========

This application makes heavy usage of Symfony2's components to create a POO model representing a book's library (loaded
from an XML or YAML source file).

Project setup
=============

1. Clone the git repository
2. Go to the root folder
3. Install Composer (http://getcomposer.org/doc/00-intro.md#installation-nix) and run `composer.phar install`
4. Install Bower (http://bower.io/#installing-bower) and run `bower install`

Execute the tests
=================

1. Follow the "Project setup" chapter
2. Run `bin/phpunit`
