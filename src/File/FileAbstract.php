<?php

/**
 * @file FileAbstract.php
 */

namespace Minisite\File;

use \ZipArchive;
use Minisite\Exception\RuntimeException;

/**
 * Class FileAbstract
 * @package Minisite\File
 */
abstract class FileAbstract implements FileInterface
{
    private $_file;
    private $_archive;

    /**
     * FileAbstract constructor.
     * @param $file
     * @param null $flags
     */
    public function __construct($file, $flags = null)
    {
        if (!empty($file)) {
            self::setFile($file);
            self::open($file, $flags);
        } else {
            throw new RuntimeException(FileStatus::getStatus(ZipArchive::ER_NOENT));
        }
    }

    /**
     * Open archive file.
     * @param $file
     */
    public static function open($file, $flags = null)
    {
        if (empty($file)) {
            throw new RuntimeException(FileStatus::getStatus(ZipArchive::ER_NOENT));
        }

        $archive = new ZipArchive();
        $open = $archive->open($file, $flags);

        if ($open !== true) {
            throw new RuntimeException(FileStatus::getStatus($open));
        }

        self::setArchive($open);
    }

    /**
     * Get a list of files in archive (array).
     * @return array
     */
    public function lists()
    {
        $list = [];

        return $list;
    }

    /**
     * Close the archive.
     * @return bool
     */
    function close()
    {
        if ($this->_archive->close() === false) {
            throw new RuntimeException(FileStatus::getStatus($this->_archive->status));
        } else {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->_file = $file;
    }

    /**
     * @return mixed
     */
    public function getArchive()
    {
        return $this->_archive;
    }

    /**
     * @param ZipArchive $archive
     */
    public function setArchive(ZipArchive $archive)
    {
        $this->_archive = $archive;
    }
}
