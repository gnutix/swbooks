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

Project setup
-------------

`vagrant up` and access the application at `http://swbooks.lo/app_dev.php/`.

Execute the tests
-----------------

`vagrant ssh -c '/vagrant/bin/phpunit'`

Coding standards
----------------

`vagrant ssh -c '/vagrant/bin/console cs'`
