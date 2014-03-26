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
    private $originalName;
    private $mimeType;
    private $size;
    private $error;

    public function __construct($pathName, $originalName, $mimeType = null, $size = 0, $error = null) {
        $this->originalName = $originalName;
        $this->mimeType = $mimeType;
        $this->size = $size;
        $this->error = $error;

        parent::__construct($pathName);
    }

    public function moveUploadedFile($directory) {
        if($this->isValid()) {
            if(!is_dir($directory)) {
                if(false === @mkdir($directory, 0777, true)) {
                    throw new FileException(sprintf("Could not create directory %s.", $directory));
                }
            } elseif (!is_writable($directory)) {
                throw new FileException(sprintf("Unable to write %s directory.", $directory));
            }

            if(!@move_uploaded_file($this->getPathname(), "{$directory}/{$this->originalName}")) {
                throw new FileException(sprintf("Failed move file %s to %s", $this->originalName, $directory));
            }

            return new UploadedFile("{$directory}/{$this->originalName}", $this->originalName);
        }
    }

    public function isValid() {
        return UPLOAD_ERR_OK === $this->error;
    }
} 