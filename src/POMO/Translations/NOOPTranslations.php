<?php
/*
 * This file is part of the POMO package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace POMO\Translations;

/**
 * Provides the same interface as Translations, but doesn't do anything
 *
 * @package POMO
 * @subpackage Translations
 */
class NOOPTranslations
{
    public $entries = array();
    public $headers = array();

    public function add_entry($entry)
    {
        return true;
    }

    public function set_header($header, $value)
    {
    }

    public function set_headers($headers)
    {
    }

    public function get_header($header)
    {
        return false;
    }

    public function translate_entry(&$entry)
    {
        return false;
    }

    public function translate($singular, $context=null)
    {
        return $singular;
    }

    public function select_plural_form($count)
    {
        return 1 == $count? 0 : 1;
    }

    public function get_plural_forms_count()
    {
        return 2;
    }

    public function translate_plural($singular, $plural, $count, $context = null)
    {
            return 1 == $count? $singular : $plural;
    }

    public function merge_with(&$other)
    {
    }
}
