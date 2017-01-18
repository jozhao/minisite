<?php

/**
 * @file FileAbstract.php
 */

namespace Minisite\File;

use \ZipArchive;
use Minisite\Exception\RuntimeException;
use Minisite\Exception\InvalidArgumentException;

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
            throw new InvalidArgumentException(FileStatus::getStatus(ZipArchive::ER_NOENT));
        }
    }

    /**
     * Open archive file.
     * @param $file
     */
    public function open($file, $flags = null)
    {
        if (empty($file)) {
            throw new InvalidArgumentException(FileStatus::getStatus(ZipArchive::ER_NOENT));
        }

        $archive = new ZipArchive();
        $open = $archive->open($file, $flags);

        if ($open !== true) {
            throw new RuntimeException(FileStatus::getStatus($open));
        }

        $this->setArchive($archive);

        return $archive;
    }

    /**
     * Exact files to given path.
     */
    public function extract($path, array $files = array())
    {
        if (empty($path)) {
            throw new InvalidArgumentException('Invalid destination path');
        }

        if ($files) {
            $this->getArchive()->extract($path, $files);
        } else {
            $this->getArchive()->extract($path);
        }

        return $this;
    }

    /**
     * Remove file from archive file.
     * @param $file
     * @return $this
     */
    public function remove($file)
    {
        $this->getArchive()->deleteName($file);

        return $this;
    }

    /**
     * Get a list of files in archive (array).
     * @return array
     */
    public function lists()
    {
        $list = [];

        for ($i = 0; $i < $this->_archive->numFiles; $i++) {
            $name = $this->_archive->getNameIndex($i);
            if ($name === false) {
                throw new RuntimeException(FileStatus::getStatus($this->_archive->status));
            }
            // Add file into lists.
            array_push($list, $name);
        }

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
