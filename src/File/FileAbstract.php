<?php

/**
 * @file FileAbstract.php
 */

namespace minisite\File;

use Alchemy\Zippy\Zippy;

/**
 * Class FileAbstract
 * @package minisite\File
 */
abstract class FileAbstract implements FileInterface
{
    private $_zippy;
    private $_archive;

    /**
     * FileAbstract constructor.
     */
    public function __construct()
    {
        $this->_zippy = Zippy::load();
    }

    /**
     * Open archive file.
     *
     * @param $file
     */
    public function open($file)
    {
        $this->_archive = $this->_zippy->open($file);
    }
}
