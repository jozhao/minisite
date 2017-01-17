<?php

/**
 * @file FileInterface.php
 */

namespace minisite\File;

/**
 * Interface FileInterface
 * @package minisite\File
 */
interface FileInterface
{
    /**
     * Open archive file.
     *
     * @param $file
     * @return mixed
     */
    public function open($file);
}
