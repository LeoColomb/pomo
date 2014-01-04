<?php
/**
 * Classes, which help reading streams of data from files.
 * Based on the classes from Danilo Segan <danilo@kvota.net>
 *
 * @version $Id: streams.php 718 2012-10-31 00:32:02Z nbachiyski $
 * @package pomo
 * @subpackage streams
 */
namespace POMO\Streams;

/**
 * Reads the contents of the file in the beginning.
 */
class CachedIntFileReader extends CachedFileReader {
	function CachedIntFileReader($filename) {
		parent::CachedFileReader($filename);
	}
}
