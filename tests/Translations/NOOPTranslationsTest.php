<?php

/**
 * POMO Unit Tests
 * NOOPTranslations Test.
 */

namespace POMO\Tests\Translations;

use PHPUnit\Framework\TestCase;
use POMO\Translations\EntryTranslations;
use POMO\Translations\NOOPTranslations;

/**
 * @property NOOPTranslations noop
 * @property EntryTranslations entry
 * @property EntryTranslations plural_entry
 */
class NOOPTranslationsTest extends TestCase
{
    public function setUp()
    {
        //parent::setUp();
        $this->noop = new NOOPTranslations();
        $this->entry = new EntryTranslations(array('singular' => 'baba'));
        $this->plural_entry = new EntryTranslations(array('singular' => 'dyado', 'plural' => 'dyados', 'translations' => array('dyadox', 'dyadoy')));
    }

    public function test_get_header()
    {
        $this->assertEquals(false, $this->noop->get_header('Content-Type'));
    }

    public function test_add_entry()
    {
        $this->noop->add_entry($this->entry);
        $this->assertEquals(array(), $this->noop->entries);
    }

    public function test_set_header()
    {
        $this->noop->set_header('header', 'value');
        $this->assertEquals(array(), $this->noop->headers);
    }

    public function test_translate_entry()
    {
        $this->noop->add_entry($this->entry);
        $this->assertEquals(false, $this->noop->translate_entry($this->entry));
    }

    public function test_translate()
    {
        $this->noop->add_entry($this->entry);
        $this->assertEquals('baba', $this->noop->translate('baba'));
    }

    public function test_select_plural()
    {
        $this->assertEquals(0, $this->noop->select_plural_form(1));
        $this->assertEquals(1, $this->noop->select_plural_form(8));
        $this->assertEquals(1, $this->noop->select_plural_form(''));
    }

    public function test_plural()
    {
        $this->noop->add_entry($this->plural_entry);
        $this->assertEquals('dyado', $this->noop->translate_plural('dyado', 'dyados', 1));
        $this->assertEquals('dyados', $this->noop->translate_plural('dyado', 'dyados', 11));
        $this->assertEquals('dyados', $this->noop->translate_plural('dyado', 'dyados', 0));
    }
}
