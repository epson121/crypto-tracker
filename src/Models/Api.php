<?php

namespace Models;


class Api
{

    protected $_url;
    protected $_cacheInstance;

    public function __construct($url) {
        $this->_url = $url;
        $this->_cacheInstance = new \Models\Cache();
    }

    public function call() {
        $cacheFileName = md5($this->_url) .  ".cache";
        $response = $this->_cacheInstance->loadCache($cacheFileName);
        if (!$response) {
            $result = null;
            $response = $this->performCurl();
            if ($response) {
                $response = json_decode($response);
                if ($response && $response->success) {
                    $this->_cacheInstance->saveCache($cacheFileName, $response);
                    $result = $response->ticker;
                } else {
                    $result = $response->error;
                }
            }
            return $result;
        } else {
            return $response->ticker;
        }
    }

    public function performCurl() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->_url
        ));

        $resp = curl_exec($curl);

        curl_close($curl);

        return $resp;
    }

}