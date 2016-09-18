<?php
/**
 * Date: 15.09.16.
 */

namespace Models;


class Cache
{
    protected $_dir;

    protected $_ttl = 300;

    public function __construct() {
        $this->_dir = $_SERVER['DOCUMENT_ROOT'] . "/var/cache/";
    }

    public function loadCache($file) {
        $fileName = $this->getFilename($file);
        if (file_exists($fileName) && (time() - $this->_ttl < filemtime($fileName))) {
            $fp = fopen($fileName, 'r');
            $data = unserialize(fread($fp, filesize($fileName)));
            return $data;
        }
        return false;
    }

    public function saveCache($file, $content) {
        $fileName = $this->getFilename($file);
        $fp = fopen($fileName, 'w');
        if ($fp) {
            fwrite($fp, serialize($content));
            fclose($fp);
        }
    }

    public function getFilename($file) {
        $fileName = $this->_dir . $file;
        return $fileName;
    }

}