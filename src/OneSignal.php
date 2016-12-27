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
     * Sends notifications to your users via OneSignal API
     * @see https://documentation.onesignal.com/reference#create-notification
     *
     * @param      array   $fields  The fields
     *
     * @return     Notification
     */
    public function createNotification(array $fields)
    {
    }

    /**
     * Stop a scheduled or currently outgoing notification
     * @see https://documentation.onesignal.com/reference#cancel-notification
     *
     * @param      string  $id     (require) Notification ID.
     * @param      string  $appid  (require) App ID
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function cancelNotification($id, $appid = '')
    {
    }

    /**
     * View the details of a single notification
     * @see     https://documentation.onesignal.com/reference#view-notification
     *
     * @param  string  $id     (require) Notification ID
     * @param  string  $appid  (require) App ID
     *
     * @return array  The result from API.
     */
    public function getNotification($id, $appid = '')
    {
    }

    /**
     * View the details of multiple notifications
     * @see  https://documentation.onesignal.com/reference#view-notifications
     *
     * @param  integer  $limit   How many notifications to return.
     *                           Max is 50. Default is 50
     * @param  integer  $offset  Result offset. Default is 0.
     *                           Results are sorted by queued_at in descending order.
     * @param  string   $appid   The app ID that you want to view notifications from
     *
     * @return array   The notifications.
     */
    public function getNotifications($limit = 50, $offset = 0, $appid = '')
    {
    }

    /**
     * Gets the details of all apps in OneSignal.
     * @see https://documentation.onesignal.com/reference#view-apps-apps
     *
     * @return string $response The response from OneSignal REST API.
     */
    public function getApps()
    {
    }

    /**
     * Gets the details of app in OneSignal.
     * @see https://documentation.onesignal.com/reference#view-an-app
     *
     * @param      string  $appid  The appid
     */
    public function getApp(string $appid = '')
    {
    }

    /**
     * Creates an application in OneSignale.
     * @see https://documentation.onesignal.com/reference#create-an-app
     *
     * @param  array  $bodyParams  Body parameters
     *
     */
    public function createApp(array $bodyParams)
    {
    }

    /**
     * Updates details of application in OneSignale.
     * @see https://documentation.onesignal.com/reference#update-an-app
     *
     * @param  array  $bodyParams  Body parameters
     *
     */
    public function updateApp(array $bodyParams)
    {
    }

    public function getDevices()
    {
    }

    public function getDevice($id)
    {
    }

    public function addDevice()
    {
    }

    public function editDevice()
    {
    }

    public function newSession()
    {
    }

    public function newPurchase()
    {
    }

    public function incrementSessionLength()
    {
    }

    public function trackOpen()
    {
    }

    /**
     * Generate a compressed CSV export of all of your current user data
     *
     * @param      <type>  $appid   The appid
     * @param      <type>  $appkey  The appkey
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function exportCSV($appid, $appkey)
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

    /**
     * Gets the response from OneSignal API or model from raw response.
     *
     * @param  string  $model  The model name ('App/Notification/Device')
     *                         default(raw) will return array after json_decode
     *
     * @return mixed  The response.
     */
    public function getResponse($model = 'raw')
    {
        $class = "\OneSignalApi\Models\{$model}";
        if (!class_exists($class)) {
            return $this->response;
        } else {
            return new $class($this->response);
        }
    }
}
