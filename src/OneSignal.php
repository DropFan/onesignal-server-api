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

        $this->setHeader();
    }

    /**
     * Sets the header for Basic HTTP Auth.
     *
     * @param      string  $appid   The appid
     * @param      string  $appkey  The appkey
     *
     * @return     self
     */
    public function setHeader($appid = '', $appkey = '')
    {
        if (!empty($appid) && !empty($appkey)) {
            $this->appid = $appid;
            $this->appkey = $appkey;
            $key = $appkey;
        } else {
            $key = $this->userkey;
        }

        $this->header = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $key
        ];
        return $this;
    }

    /**
     * Sends notifications to your users via OneSignal API
     * @see https://documentation.onesignal.com/reference#create-notification
     *
     * @param      array   $fields  The fields
     *
     * @return     array
     */
    public function createNotification(array $fields)
    {

        if (isset($fields['app_ids']) && !empty($fields['app_ids'])) {
            $this->appid = '';
            $this->appkey = '';
        } elseif (isset($fields['app_id']) && !empty($fields['app_id'])) {
            $this->appid = $fields['app_id'];
            $this->appkey = isset($fields['appkey']) ? $fields['appkey'] : $this->userkey;
        } else {
            $fields['app_id'] = $this->appid;
        }
        $this->setHeader($this->appid, $this->appkey);

        $this->apiUrl = self::BASE_URL . 'notifications';
        $this->method = 'POST';
        $this->fields = json_encode($fields);

        return $this->sendRequest()->getResponse();
    }

    /**
     * Stop a scheduled or currently outgoing notification
     * @see https://documentation.onesignal.com/reference#cancel-notification
     *
     * @param      string  $id     (require) Notification ID.
     * @param      string  $appid  (require) App ID
     * @param      string  $appkey  The appkey
     *
     * @return     array
     */
    public function cancelNotification($id, $appid = '', $appkey = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }
        if ($appkey) {
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . "notifications/{$id}?app_id={$this->appid}";
        $this->method = 'DELETE';

        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse();
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
    public function getNotification(string $id, $appid = '', $appkey = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }
        if ($appkey) {
            $this->appkey = $appkey;
        }
        $this->setHeader($this->appid, $this->appkey);
        $this->apiUrl = self::BASE_URL . "notifications/{$id}?app_id={$this->appid}";
        $this->method = 'GET';

        return $this->sendRequest()->getResponse('Notification');
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
     * @param  string   $appkey  The app key
     *
     * @return array   The notifications.
     */
    public function getNotifications($limit = 50, $offset = 0, $appid = '', $appkey = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }
        if ($appkey) {
            $this->appkey = $appkey;
        }

        $this->setHeader($this->appid, $this->appkey);
        $this->apiUrl = self::BASE_URL . "notifications?app_id={$this->appid}";
        $this->apiUrl .= "&limit={$limit}&offset={$offset}";
        $this->method = 'GET';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Gets the details of all apps in OneSignal.
     * @see https://documentation.onesignal.com/reference#view-apps-apps
     *
     * @return string $response The response from OneSignal REST API.
     */
    public function getApps()
    {
        $this->apiUrl = self::BASE_URL . 'apps';
        $this->method = 'GET';

        $this->setHeader();

        return $this->sendRequest()->getResponse();
    }

    /**
     * Gets the details of app in OneSignal.
     * @see        https://documentation.onesignal.com/reference#view-an-app
     *
     * @param      string  $appid   The appid
     * @param      string  $appkey  The appkey
     *
     * @return     <type>  The application.
     */
    public function getApp($appid = '', $appkey = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }
        if ($appkey) {
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . 'apps/' . $this->appid;
        $this->method = 'GET';

        $this->setHeader();

        return $this->sendRequest()->getResponse('App');
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
        $this->apiUrl = self::BASE_URL . 'apps';
        $this->method = 'POST';

        $this->setHeader();

        foreach ($bodyParams as $k => $v) {
            if ($v === '' || $v === null || $v === []) {
                unset($bodyParams[$k]);
            }
        }

        $this->fields = json_encode($bodyParams);

        return $this->sendRequest()->getResponse('App');
    }

    /**
     * Updates details of application in OneSignale.
     * @see        https://documentation.onesignal.com/reference#update-an-app
     *
     * @param      string  $appid       The appid
     * @param      array   $bodyParams  Body parameters
     *
     * @return     array or app
     */
    public function updateApp(array $bodyParams, $appid = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }

        $this->apiUrl = self::BASE_URL . 'apps/' . $this->appid;
        $this->method = 'PUT';

        $this->setHeader();

        foreach ($bodyParams as $k => $v) {
            if ($v === '' || $v === null || $v === []) {
                unset($bodyParams[$k]);
            }
        }

        $this->fields = json_encode($bodyParams);

        return $this->sendRequest()->getResponse('App');
    }

    /**
     * View the details of multiple devices in one of your OneSignal apps
     * @see https://documentation.onesignal.com/reference#view-devices
     *
     * @param  integer  $limit   How many devices to return. Max is 300. Default is 300
     * @param  integer  $offset  Result offset. Default is 0. Results are sorted by id;
     * @param  string   $appid   The app ID that you want to view devices from
     *
     * @return array   The devices.
     */
    public function getDevices($limit = 300, $offset = 0, $appid = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }
        $this->apiUrl = self::BASE_URL . "players?app_id={$this->appid}";
        $this->apiUrl .= "&limit={$limit}&offset={$offset}";
        $this->method = 'GET';
        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse();
    }

    /**
     * View the details of an existing device in one of your OneSignal apps
     * @see https://documentation.onesignal.com/reference#view-device
     *
     * @param  string  $id     Player's OneSignal ID
     * @param  string  $appid  Your app_id for this device
     *
     * @return array  The device.
     */
    public function getDevice(string $id, $appid = '')
    {
        if ($appid) {
            $this->appid = $appid;
        }

        $this->apiUrl = self::BASE_URL . "players/{$id}";
        $this->apiUrl .= "?app_id={$this->appid}";

        $this->method = 'GET';
        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Register a new device to one of your OneSignal apps
     * @see https://documentation.onesignal.com/reference#add-a-device
     *
     * @param      array   $fields  The body params
     *
     * @return     array
     */
    public function addDevice(array $fields)
    {
        $this->apiUrl = self::BASE_URL . "players/";
        $this->method = 'POST';

        if (!isset($fields['app_id']) || empty($fields['app_id'])) {
            $fields['app_id'] = $this->appid;
        }
        $this->fields = json_encode($fields);
        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Update an existing device in one of your OneSignal apps
     * @see https://documentation.onesignal.com/reference#edit-device
     *
     * @param      string  $id      The device's OneSignal ID
     * @param      array   $fields  The body params
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function editDevice(string $id, array $fields)
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}";
        $this->method = 'PUT';
        $this->fields = json_encode($fields);
        $this->setHeader();

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Update a device's session information
     * @see https://documentation.onesignal.com/reference#new-session
     *
     * @param      string  $id      Player's OneSignal ID
     * @param      array   $fields  body params array
     *                              [
     *                                  'identifier' => '',
     *                                  'language' => '',
     *                                  'timezone' => 0,
     *                                  'game_version' => '',
     *                                  'device_os' => '',
     *                                  'ad_id' => '',
     *                                  'sdk' => '',
     *                                  'tags' => [],
     *                              ];
     * @return     array
     */
    public function onSession(string $id, $fields = [])
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_session";
        $this->method = 'POST';
        $this->setHeader();
        $this->fields = json_encode($fields);

        return $this->sendRequest()->getResponse();
    }

    /**
     * Track a new purchase in your app
     * @see https://documentation.onesignal.com/reference#new-purchase
     *
     * @param      string   $id        Player's OneSignal ID
     * @param      array    $purchase  The purchase array
     * @param      boolean  $existing  The existing
     *
     * @return     array
     */
    public function onPurchase(string $id, array $purchase, $existing = false)
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_purchase";
        $this->method = 'POST';
        $this->setHeader();

        $fields['purchase'] = $purchase;
        if ($esisting) {
            $fields['existing'] = true;
        }
        $this->fields = json_encode($fields);

        return $this->sendRequest()->getResponse();
    }

    /**
     * Update a device's session length upon app resuming
     * @see https://documentation.onesignal.com/reference#increment-session-length
     *
     * @param      string   $id           Player's OneSignal ID
     * @param      integer  $active_time  The active time
     * @param      string   $state        The state
     *
     * @return     array    ( description_of_the_return_value )
     */
    public function onFocus(string $id, $active_time = 60, $state = 'ping')
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_focus";
        $this->method = 'POST';
        $this->setHeader();

        $fields = [
            'state' => 'ping',
            'active_time' => $active_time
        ];
        $this->fields = json_encode($fields);

        return $this->sendRequest()->getResponse();
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

        if ($method !== 'GET' && in_array($method, self::METHODS)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($fields)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        $this->lastErrno = curl_errno($ch);
        $this->lastError = curl_error($ch);
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

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
    public function getResponse($model = '')
    {
        $class = __NAMESPACE__.'\\Models\\'.$model;

        if (!empty($model)
            && !isset($this->response['error'])
            && is_array($this->response)
            && class_exists($class)) {
            return new $class($this->response);
        }
        return $this->response;
    }
}
