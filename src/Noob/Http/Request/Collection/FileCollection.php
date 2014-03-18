<?php
/**
 * Created by PhpStorm.
 * User: willy
 * Date: 3/7/14
 * Time: 9:21 AM
 */

namespace Noob\Http\Request\Collection;

use Noob\Http\File\UploadedFile;

/**
 * Class FileCollection
 * @package Noob\Http\Request\
 */
class FileCollection extends ParameterCollection {

    private static $fileKeys = array('error', 'name', 'size', 'tmp_name', 'type');

    public function __construct(array $parameters = array()) {
        $this->replaceCollection($parameters);
    }

    /**
     * Replace parent collection with new collection of file
     *
     * @param array $files
     */
    public function replaceCollection(array $files = array()) {
        parent::exchangeArray(array());
        $this->addFile($files);
    }

    /**
     * Set an parameters
     *
     * @param array $files
     * @return ParameterCollection|void
     */
    public function addFile(array $files = array()) {
        foreach ($files as $key => $file) {
            $this->set($key, $file);
        }
    }

    /**
     * Add collection
     *
     * @param $key
     * @param $value
     * @throws \InvalidArgumentException
     */
    public function set($key, $value) {
        if (!is_array($value) and !$value instanceof UploadedFile) {
            throw new \InvalidArgumentException('File must be an array or an instance of UploadedFile.');
        }

        parent::add($key, $this->convertToUploadedFile($value));
    }

    /**
     * Converts file information to uploadedFile instances
     *
     * @param $file
     * @return UploadedFile instance
     */
    protected function convertToUploadedFile($file) {
        if ($file instanceof UploadedFile) return $file;

        if (is_array($file)) {
            $keys = array_keys($file);

            if (sort($keys) == self::$fileKeys) {
                if (UPLOAD_ERR_NO_FILE == $file['error']) {
                    $file = null;
                } else {
                    $file = new UploadedFile(
                        $file['tmp_name'],
                        $file['name'],
                        $file['type'],
                        $file['size'],
                        $file['error']
                    );
                }
            }
        } else {
            $file = array_walk($file, array($this, 'convertToUploadedFile'));
        }

        return $file;
    }
} 