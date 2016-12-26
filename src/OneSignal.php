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
}
