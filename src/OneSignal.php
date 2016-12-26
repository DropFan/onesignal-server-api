<?php
namespace OneSignalApi;

use OneSignalApi\Models\App as App;

class OneSignal
{
    const BASE_URL = 'https://onesignal.com/api/v1/';
    const METHODS = ['GET', 'POST', 'PUT', 'DELETE'];

    public $apiUrl;
    public $method;
    public $header;
    public $fields;
    public $response;

    public function __construct($config)
    {
    }
    /**
     * Sends a request to OneSignal Server.
     *
     * @param      string  $url     The url
     * @param      string  $method  The method
     * @param      array   $header  The header
     * @param      array   $fields  The fields
     *
     * @return     string   The response from OneSignal API
     */
    public function sendRequest(
        $url = '',
        $method = '',
        $header = [],
        $fields = []
    ) {
    }
    public function getResponse()
    {
        return $this->response;
    }
}
