[![Build Status](https://travis-ci.org/gnutix/swbooks.png?branch=dev)](https://travis-ci.org/gnutix/swbooks)

Star Wars Library
=================

This single web page application allows me to list my Star Wars books (with some books information), featuring the
number of readings I've done and copies I own for each books releases.

Development
===========

This application use my Gnutix\Library module (which I may publish sometimes on packagist.org), which allows to create a
library from various source files (currently the only implementation being an XML file ;)) and provide a POO structure
to work with.

Project setup
=============

Clone the git repository, install Composer (http://getcomposer.org/) and run `composer.phar install` in the
project's root folder.

Tests suite
===========

To execute the tests suite, first setup the project and then simply run `bin/phpunit` in the project's root folder.
