<?php

/**
 * POMO Unit Tests
 * PO Test.
 */

namespace POMO\Tests;

use POMO\PO;
use POMO\Translations\EntryTranslations;

/**
 * @property string a90
 * @property string po_a90
 */
class POTest extends POMOTestCase
{
    public function setUp()
    {
        $this->a90 = str_repeat('a', 90);
        $this->po_a90 = "\"$this->a90\"";
    }

    public function test_prepend_each_line()
    {
        $po = new PO();
        $this->assertEquals('baba_', $po->prepend_each_line('', 'baba_'));
        $this->assertEquals('baba_dyado', $po->prepend_each_line('dyado', 'baba_'));
        $this->assertEquals("# baba\n# dyado\n# \n", $po->prepend_each_line("baba\ndyado\n\n", '# '));
    }

    public function test_poify()
    {
        $po = new PO();
        //simple
        $this->assertEquals('"baba"', $po->poify('baba'));
        //long word
        $this->assertEquals($this->po_a90, $po->poify($this->a90));
        // tab
        $this->assertEquals('"ba\tba"', $po->poify("ba\tba"));
        // do not add leading empty string of one-line string ending on a newline
        $this->assertEquals('"\\\\a\\\\n\\n"', $po->poify("\a\\n\n"));
        // backslash
        $this->assertEquals('"ba\\\\ba"', $po->poify('ba\\ba'));
        // random string
        $src = 'Categories can be selectively converted to tags using the <a href="%s">category to tag converter</a>.';
        $this->assertEquals('"Categories can be selectively converted to tags using the <a href=\\"%s\\">category to tag converter</a>."', $po->poify($src));
    }

    public function test_unpoify()
    {
        $po = new PO();
        $this->assertEquals('baba', $po->unpoify('"baba"'));
        $this->assertEquals("baba\ngugu", $po->unpoify('"baba\n"'."\t\t\t\n".'"gugu"'));
        $this->assertEquals($this->a90, $po->unpoify($this->po_a90));
        $this->assertEquals('\\t\\n', $po->unpoify('"\\\\t\\\\n"'));
        // wordwrapped
        $this->assertEquals('babadyado', $po->unpoify("\"\"\n\"baba\"\n\"dyado\""));
    }

    public function test_export_entry()
    {
        $po = new PO();
        $entry = new EntryTranslations(array('singular' => 'baba'));
        $this->assertEquals("msgid \"baba\"\nmsgstr \"\"", $po->export_entry($entry));
        // plural
        $entry = new EntryTranslations(array('singular' => 'baba', 'plural' => 'babas'));
        $this->assertEquals('msgid "baba"
msgid_plural "babas"
msgstr[0] ""
msgstr[1] ""', $po->export_entry($entry));
        $entry = new EntryTranslations(array('singular' => 'baba', 'translator_comments' => "baba\ndyado"));
        $this->assertEquals('#  baba
#  dyado
msgid "baba"
msgstr ""', $po->export_entry($entry));
        $entry = new EntryTranslations(array('singular' => 'baba', 'extracted_comments' => 'baba'));
        $this->assertEquals('#. baba
msgid "baba"
msgstr ""', $po->export_entry($entry));
        $entry = new EntryTranslations(array(
            'singular' => 'baba',
            'extracted_comments' => 'baba',
            'references' => range(1, 29), ));
        $this->assertEquals('#. baba
#: 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28
#: 29
msgid "baba"
msgstr ""', $po->export_entry($entry));
        $entry = new EntryTranslations(array('singular' => 'baba', 'translations' => array()));
        $this->assertEquals("msgid \"baba\"\nmsgstr \"\"", $po->export_entry($entry));

        $entry = new EntryTranslations(array('singular' => 'baba', 'translations' => array('куку', 'буку')));
        $this->assertEquals("msgid \"baba\"\nmsgstr \"куку\"", $po->export_entry($entry));

        $entry = new EntryTranslations(array('singular' => 'baba', 'plural' => 'babas', 'translations' => array('кукубуку')));
        $this->assertEquals('msgid "baba"
msgid_plural "babas"
msgstr[0] "кукубуку"', $po->export_entry($entry));

        $entry = new EntryTranslations(array('singular' => 'baba', 'plural' => 'babas', 'translations' => array('кукубуку', 'кукуруку', 'бабаяга')));
        $this->assertEquals('msgid "baba"
msgid_plural "babas"
msgstr[0] "кукубуку"
msgstr[1] "кукуруку"
msgstr[2] "бабаяга"', $po->export_entry($entry));
        // context
        $entry = new EntryTranslations(array('context' => 'ctxt', 'singular' => 'baba', 'plural' => 'babas', 'translations' => array('кукубуку', 'кукуруку', 'бабаяга'), 'flags' => array('fuzzy', 'php-format')));
        $this->assertEquals('#, fuzzy, php-format
msgctxt "ctxt"
msgid "baba"
msgid_plural "babas"
msgstr[0] "кукубуку"
msgstr[1] "кукуруку"
msgstr[2] "бабаяга"', $po->export_entry($entry));
    }

    public function test_export_entries()
    {
        $entry = new EntryTranslations(array('singular' => 'baba'));
        $entry2 = new EntryTranslations(array('singular' => 'dyado'));
        $po = new PO();
        $po->add_entry($entry);
        $po->add_entry($entry2);
        $this->assertEquals("msgid \"baba\"\nmsgstr \"\"\n\nmsgid \"dyado\"\nmsgstr \"\"", $po->export_entries());
    }

    public function test_export_headers()
    {
        $po = new PO();
        $po->set_header('Project-Id-Version', 'pomo/pomo tests');
        $po->set_header('POT-Creation-Date', '1972-03-23 07:00+0000');
        $this->assertEquals("msgid \"\"\nmsgstr \"\"\n\"Project-Id-Version: pomo/pomo tests\\n\"\n\"POT-Creation-Date: 1972-03-23 07:00+0000\\n\"", $po->export_headers());
    }

    public function test_export()
    {
        $po = new PO();
        $entry = new EntryTranslations(array('singular' => 'baba'));
        $entry2 = new EntryTranslations(array('singular' => 'dyado'));
        $po->set_header('Project-Id-Version', 'pomo/pomo tests');
        $po->set_header('POT-Creation-Date', '1972-03-23 07:00+0000');
        $po->add_entry($entry);
        $po->add_entry($entry2);
        $this->assertEquals("msgid \"baba\"\nmsgstr \"\"\n\nmsgid \"dyado\"\nmsgstr \"\"", $po->export(false));
        $this->assertEquals("msgid \"\"\nmsgstr \"\"\n\"Project-Id-Version: pomo/pomo tests\\n\"\n\"POT-Creation-Date: 1972-03-23 07:00+0000\\n\"\n\nmsgid \"baba\"\nmsgstr \"\"\n\nmsgid \"dyado\"\nmsgstr \"\"", $po->export());
    }

    public function test_export_to_file()
    {
        $po = new PO();
        $entry = new EntryTranslations(array('singular' => 'baba'));
        $entry2 = new EntryTranslations(array('singular' => 'dyado'));
        $po->set_header('Project-Id-Version', 'pomo/pomo tests');
        $po->set_header('POT-Creation-Date', '1972-03-23 07:00+0000');
        $po->add_entry($entry);
        $po->add_entry($entry2);

        $temp_fn = $this->temp_filename();
        $po->export_to_file($temp_fn, false);
        $this->assertEquals($po->export(false), file_get_contents($temp_fn));

        $temp_fn2 = $this->temp_filename();
        $po->export_to_file($temp_fn2);
        $this->assertEquals($po->export(), file_get_contents($temp_fn2));
    }

    public function test_import_from_file()
    {
        $po = new PO();
        $res = $po->import_from_file(__DIR__.'/data/simple.po');
        $this->assertEquals(true, $res);

        $this->assertEquals(array('Project-Id-Version' => 'pomo/pomo tests', 'Plural-Forms' => 'nplurals=2; plural=n != 1;'), $po->headers);

        $simple_entry = new EntryTranslations(array('singular' => 'moon'));
        $this->assertEquals($simple_entry, $po->entries[$simple_entry->key()]);

        $all_types_entry = new EntryTranslations(array('singular' => 'strut', 'plural' => 'struts', 'context' => 'brum',
            'translations' => array('ztrut0', 'ztrut1', 'ztrut2'), ));
        $this->assertEquals($all_types_entry, $po->entries[$all_types_entry->key()]);

        $multiple_line_entry = new EntryTranslations(array('singular' => 'The first thing you need to do is tell Blogger to let WordPress access your account. You will be sent back here after providing authorization.', 'translations' => array("baba\ndyadogugu")));
        $this->assertEquals($multiple_line_entry, $po->entries[$multiple_line_entry->key()]);

        $multiple_line_all_types_entry = new EntryTranslations(array('context' => 'context', 'singular' => 'singular',
            'plural' => 'plural', 'translations' => array('translation0', 'translation1', 'translation2'), ));
        $this->assertEquals($multiple_line_all_types_entry, $po->entries[$multiple_line_all_types_entry->key()]);

        $comments_entry = new EntryTranslations(array('singular' => 'a', 'translator_comments' => "baba\nbrubru",
            'references' => array('wp-admin/x.php:111', 'baba:333', 'baba'), 'extracted_comments' => 'translators: buuu',
            'flags' => array('fuzzy'), ));
        $this->assertEquals($comments_entry, $po->entries[$comments_entry->key()]);

        $end_quote_entry = new EntryTranslations(array('singular' => 'a"'));
        $this->assertEquals($end_quote_entry, $po->entries[$end_quote_entry->key()]);
    }

    public function test_import_from_entry_file_should_give_false()
    {
        $po = new PO();
        $this->assertFalse($po->import_from_file(__DIR__.'/data/empty.po'));
    }

    public function test_import_from_file_with_windows_line_endings_should_work_as_with_unix_line_endings()
    {
        $po = new PO();
        $this->assertTrue($po->import_from_file(__DIR__.'/data/windows-line-endings.po'));
        $this->assertEquals(1, count($po->entries));
    }

    //TODO: add tests for bad files
}
