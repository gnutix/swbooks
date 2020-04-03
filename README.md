[![Build Status](https://github.com/gnutix/swbooks/workflows/Code_Checks/badge.svg)](https://github.com/gnutix/swbooks/actions?query=workflow%3ACode_Checks)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gnutix/swbooks/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gnutix/swbooks/?branch=master)
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
* `php -S localhost:8000 -t public/`

Access the application at `http://localhost:8000/`.

Useful commands
---------------

* `composer list | grep 'script as defined'` to list all custom scripts, then execute with `composer [script-name]`

DEPRECATED: Bower
-----------------

The project used Bower back in the day with the following `bower.json` : 

```json
{
  "name": "swbooks",
  "private": true,
  "dependencies": {
    "jquery": "latest",
    "bootstrap": "~3.0.1",
    "paul-irish-jquery-unique-duck-punching": "*",
    "brandon-aaron-jquery-outerhtml-function": "*",
    "edward-hotchkiss-wrapper": "~0.0.3"
  }
}
```

It should be migrated to more recent systems at some point.
