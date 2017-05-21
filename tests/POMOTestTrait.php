<?php

/**
 * POMO Unit Tests
 * POMO Test Trait
 */

namespace POMO\Tests;

trait POMOTestTrait
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

    /**
     * @param $text
     * @return mixed
     */
    public function replace_r_n($text)
    {
        return str_replace("\r\n", "\n", $text);
    }
}
