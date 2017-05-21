<?php

/**
 * POMO Unit Tests Bootstrap
 */

$loader = require __DIR__ . "/../vendor/autoload.php";


/**
* Returns the name of a temporary file
*/
function temp_filename() {
    $tmp_dir = realpath(__DIR__);
    return tempnam($tmp_dir, 'pomo');
}

function replace_r_n($text) {
    return str_replace("\r\n", "\n", $text);
}

// PHPUnit 6 compatibility for previous versions
if (class_exists('PHPUnit\Runner\Version' ) && version_compare( PHPUnit\Runner\Version::id(), '6.0', '>=' ) ) {
    class_alias('PHPUnit\Framework\Assert',        'PHPUnit_Framework_Assert');
    class_alias('PHPUnit\Framework\TestCase',      'PHPUnit_Framework_TestCase');
    class_alias('PHPUnit\Framework\Error\Error',   'PHPUnit_Framework_Error');
    class_alias('PHPUnit\Framework\Error\Notice',  'PHPUnit_Framework_Error_Notice');
    class_alias('PHPUnit\Framework\Error\Warning', 'PHPUnit_Framework_Error_Warning');
}

