<?php

/**
 * POMO Unit Tests Bootstrap
 */

$loader = require __DIR__ . "/../vendor/autoload.php";

// PHPUnit 6 compatibility for previous versions
if (!class_exists('PHPUnit\Runner\Version') && class_exists('PHPUnit_Runner_Version')) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}
