<?php

/**
 * POMO Unit Tests
 * MO Test
 *
 * @copyright 2014 Leo Colombaro
 * @license GPL
 */

namespace POMO;

class MOTest extends \PHPUnit_Framework_TestCase
{

    public function test_translation()
    {
        $mo = new MO();
        $mo->import_from_file(__DIR__.'/data/translations.mo');

        $this->assertEquals('Outils', $mo->translate('Tools'));
    }

}
