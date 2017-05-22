<?php

/**
 * POMO Unit Tests
 * POMO Test Trait
 */

namespace POMO\Tests;

use PHPUnit\Framework\TestCase;

abstract class POMOTestCase extends TestCase
{
    /**
     * Returns the name of a temporary file
     *
     * @return bool|string
     */
    public function temp_filename()
    {
        $tmp_dir = realpath(__DIR__.'/data');
        return tempnam($tmp_dir, 'pomo');
    }
}
