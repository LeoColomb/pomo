<?php

/**
 * POMO Unit Tests
 * EntryTranslations Test
 */

namespace POMO\Tests\Translations;

use PHPUnit\Framework\TestCase;
use POMO\Translations\EntryTranslations;

class EntryTranslationsTest extends TestCase
{
    public function test_create_entry()
    {
        // no singular => empty object
        $entry = new EntryTranslations();
        $this->assertNull($entry->singular);
        $this->assertNull($entry->plural);
        $this->assertFalse($entry->is_plural);
        // args -> members
        $entry = new EntryTranslations(array(
            'singular' => 'baba',
            'plural' => 'babas',
            'translations' => array('баба', 'баби'),
            'references' => 'should be array here',
            'flags' => 'baba',
        ));
        $this->assertEquals('baba', $entry->singular);
        $this->assertEquals('babas', $entry->plural);
        $this->assertTrue($entry->is_plural);
        $this->assertEquals(array('баба', 'баби'), $entry->translations);
        $this->assertEquals(array(), $entry->references);
        $this->assertEquals(array(), $entry->flags);
    }

    public function test_key()
    {
        $entry_baba = new EntryTranslations(array('singular' => 'baba',));
        $entry_dyado = new EntryTranslations(array('singular' => 'dyado',));
        $entry_baba_ctxt = new EntryTranslations(array('singular' => 'baba', 'context' => 'x'));
        $entry_baba_plural = new EntryTranslations(array('singular' => 'baba', 'plural' => 'babas'));
        $this->assertEquals($entry_baba->key(), $entry_baba_plural->key());
        $this->assertNotEquals($entry_baba->key(), $entry_baba_ctxt->key());
        $this->assertNotEquals($entry_baba_plural->key(), $entry_baba_ctxt->key());
        $this->assertNotEquals($entry_baba->key(), $entry_dyado->key());
    }
}
