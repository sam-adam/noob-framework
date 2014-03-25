<?php
/**
 * Created by PhpStorm.
 * User: willy
 * Date: 3/25/14
 * Time: 10:48 PM
 */

namespace Noob\Http\Request\Collection;


final class FileNormalizer {
    public static function Normalize(array $files) {
        $newFiles = array();
        foreach($files as $file) {
            if(isset($file['name']) && is_array($file['name'])) {
                foreach($file as $tag => $parts) {
                    foreach($parts as $i => $data) {
                        $newFiles[$i][$tag] = $file[$tag][$i];
                    }
                }
            }
        }

        return $newFiles;
    }
} 