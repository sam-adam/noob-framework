<?php
/**
 * Created by PhpStorm.
 * User: willy
 * Date: 3/7/14
 * Time: 6:20 PM
 */

namespace Noob\Http\File;
use Noob\Http\File\Exception\FileException;

/**
 * Class UploadedFile
 * @package Noob\Http\File
 */
class UploadedFile extends \SplFileInfo {
    private $filename;
    private $mimeType;
    private $size;
    private $error;

    public function __construct($tmpName, $fileName, $mimeType = null, $size = 0, $error = null) {
        $this->filename = $fileName;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->error = $error;

        parent::__construct($tmpName);
    }

    public function moveUploadedFile($directory) {
        if(UPLOAD_ERR_OK === $this->error) {
            if(!is_dir($directory)) {
                if(false === @mkdir($directory, 0777, true)) {
                    throw new FileException(sprintf("Could not create directory %s.", $directory));
                }
            } elseif (!is_writable($directory)) {
                throw new FileException(sprintf("Unable to write %s directory.", $directory));
            }
        }
    }
} 