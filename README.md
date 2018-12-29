POMO
====

[![Build Status](https://travis-ci.org/LeoColomb/pomo.svg?branch=master)](https://travis-ci.org/LeoColomb/pomo)
[![Latest Release](https://img.shields.io/packagist/v/pomo/pomo.svg)](https://packagist.org/packages/pomo/pomo)

**Gettext library to translate with I18n**.  
[Why use it](https://codex.wordpress.org/I18n_for_WordPress_Developers).

Usage
-----
```php
<?php
use POMO\MO;

// Create MO object
$translations = new MO();

// Import MO file
$translations->import_from_file($the_mo_filepath);

// Translate
$translations->translate($text);
```

Installation
------------
The easiest way to install POMO is via [composer](http://getcomposer.org/).  

```shell
composer require pomo/pomo
```

```php
<?php
require 'vendor/autoload.php';
use POMO\MO;
...
```

Requirements
------------
POMO works with PHP 5.3 or above.

License
-------
POMO is licensed under the [GPLv2 License](LICENSE).
