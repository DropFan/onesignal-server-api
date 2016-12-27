<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2016 DropFan <DropFan@Gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package    OneSignal-server-api
 * @author     DropFan <DropFan@Gmail.com>
 * @copyright  2016 DropFan.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    0.1
 * @link       https://github.com/DropFan/onesignal-server-api
 */
namespace OneSignalApi;


class OneSignal
{
    const BASE_URL = 'https://onesignal.com/api/v1/';
    const METHODS = ['GET', 'POST', 'PUT', 'DELETE'];

    public $config = [];
    public $apps = [];

    public $appid;
    public $appkey;

    public $apiUrl;
    public $method;
    public $header;
    public $fields;

    public $response;

    public function __construct($config)
    {
        $this->config = $config;

        $this->apps = isset($config['apps']) ? $config['apps'] : [];
        $this->userkey = isset($config['userkey']) ? $config['userkey'] : '';
        $this->appid = isset($config['appid']) ? $config['appid'] : '';
        $this->appkey = isset($config['appkey']) ? $config['appkey'] : '';

        $this->header = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $this->userkey
        ];
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
        if (!$url) {
            $url = $this->apiUrl;
        }

        if (!$method || !in_array($method, self::METHODS)) {
            $method = $this->method;
        }

        if (!$header) {
            $header = $this->header;
        }

        if (!$fields) {
            $fields = $this->fields;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        $this->responseRaw = $response;
        $this->response = json_decode($response, 1);
        curl_close($ch);

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
