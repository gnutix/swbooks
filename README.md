[![Build Status](https://travis-ci.org/gnutix/swbooks.png?branch=master)](https://travis-ci.org/gnutix/swbooks)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e275cb12-5729-4490-baad-a3898fd71ff9/mini.png)](https://insight.sensiolabs.com/projects/e275cb12-5729-4490-baad-a3898fd71ff9)

Star Wars Library
=================

This single web page application allows me to list my Star Wars books (with some books information), featuring the
number of readings I've done and copies I own for each books releases.

This application makes heavy usage of Symfony2's components to create a POO model representing a book's library (loaded
from an XML or YAML source file).

Project setup
-------------

* `composer install`
* `bower install` (maybe `sudo snap install bower` first - PS: this is *not* BowerPHP)
* `php -S localhost:8000 -t web/`

Access the application at `http://localhost:8000/`.

Execute the tests
-----------------

`composer tests`

Coding standards
----------------

`composer cs`
