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

Clone the git repository, install Composer (http://getcomposer.org/) and run `composer.phar install` in the
project's root folder.

Tests suite
===========

To execute the tests suite, first setup the project and then simply run `bin/phpunit` in the project's root folder.
