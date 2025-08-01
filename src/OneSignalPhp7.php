<?php
/**
 * MIT License.
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
 * @author    DropFan <DropFan@Gmail.com>
 * @copyright 2016 DropFan.
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version 1.0 - PHP 7 Enhanced Version
 *
 * @link https://github.com/DropFan/onesignal-server-api
 */

declare(strict_types=1);

namespace OneSignalApi;

/**
 * OneSignal API Wrapper for PHP 7+
 * 
 * This class provides a PHP 7 enhanced interface to the OneSignal REST API
 * with type declarations, return types, and other PHP 7+ features.
 */
class OneSignalPhp7 implements Contracts\OneSignalInterface
{
    private const BASE_URL = 'https://onesignal.com/api/v1/';
    private const METHODS = ['GET', 'POST', 'PUT', 'DELETE'];

    public array $config = [];
    public array $apps = [];
    public string $userkey;
    public string $appid;
    public string $appkey;

    public string $apiUrl = '';
    public string $method = 'GET';
    public array $header = [];
    public string $fields = '';

    public ?array $response = null;
    public ?string $responseRaw = null;

    public int $lastErrno = 0;
    public string $lastError = '';
    public int $lastHttpCode = 0;

    /**
     * Constructor
     *
     * @param array $config Configuration array
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->apps = $config['apps'] ?? [];
        $this->userkey = $config['userkey'] ?? '';
        $this->appid = $config['appid'] ?? '';
        $this->appkey = $config['appkey'] ?? '';

        $this->setHeader();
    }

    /**
     * Sets the header for Basic HTTP Auth.
     *
     * @param string $appid  The appid
     * @param string $appkey The appkey
     *
     * @return self
     */
    public function setHeader(string $appid = '', string $appkey = ''): self
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
            'Authorization: Basic ' . $key,
        ];

        return $this;
    }

    /**
     * Sends notifications to your users via OneSignal API.
     *
     * @see https://documentation.onesignal.com/reference#create-notification
     *
     * @param array $fields The fields
     *
     * @return array
     */
    public function createNotification(array $fields): array
    {
        if (isset($fields['app_ids']) && !empty($fields['app_ids'])) {
            $this->appid = '';
            $this->appkey = '';
        } elseif (isset($fields['app_id']) && !empty($fields['app_id'])) {
            $this->appid = $fields['app_id'];
            $this->appkey = $fields['appkey'] ?? $this->userkey;
        } else {
            $fields['app_id'] = $this->appid;
        }
        $this->setHeader($this->appid, $this->appkey);

        $this->apiUrl = self::BASE_URL . 'notifications';
        $this->method = 'POST';
        $this->fields = json_encode($fields) ?: '{}';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Stop a scheduled or currently outgoing notification.
     *
     * @see https://documentation.onesignal.com/reference#cancel-notification
     *
     * @param string $id     (require) Notification ID
     * @param string $appid  App ID
     * @param string $appkey The appkey
     *
     * @return array
     */
    public function cancelNotification(string $id, string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . "notifications/{$id}?app_id={$this->appid}";
        $this->method = 'DELETE';
        $this->fields = '';
        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse();
    }

    /**
     * View the details of a single notification.
     *
     * @see https://documentation.onesignal.com/reference#view-notification
     *
     * @param string $id    (require) Notification ID
     * @param string $appid (require) App ID
     * @param string $appkey App key
     *
     * @return array the result from API
     */
    public function getNotification(string $id, string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }

        $this->setHeader($this->appid, $this->appkey);
        $this->apiUrl = self::BASE_URL . "notifications/{$id}?app_id={$this->appid}";
        $this->method = 'GET';
        $this->fields = '';

        return $this->sendRequest()->getResponse('Notification');
    }

    /**
     * View the details of multiple notifications.
     *
     * @see https://documentation.onesignal.com/reference#view-notifications
     *
     * @param int    $limit  How many notifications to return.
     *                       Max is 50. Default is 50.
     * @param int    $offset Result offset. Default is 0.
     *                       Results are sorted by queued_at in descending order.
     * @param string $appid  The app ID that you want to view notifications from
     * @param string $appkey The app key
     *
     * @return array the notifications
     */
    public function getNotifications(int $limit = 50, int $offset = 0, string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }

        $this->setHeader($this->appid, $this->appkey);
        $this->apiUrl = self::BASE_URL . "notifications?app_id={$this->appid}";
        $this->apiUrl .= "&limit={$limit}&offset={$offset}";
        $this->method = 'GET';
        $this->fields = '';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Gets the details of all apps in OneSignal.
     *
     * @see https://documentation.onesignal.com/reference#view-apps-apps
     *
     * @return array response from API
     */
    public function getApps(): array
    {
        $this->apiUrl = self::BASE_URL . 'apps';
        $this->method = 'GET';
        $this->fields = '';
        $this->setHeader();

        return $this->sendRequest()->getResponse();
    }

    /**
     * Gets the details of app in OneSignal.
     *
     * @see https://documentation.onesignal.com/reference#view-an-app
     *
     * @param string $appid  The appid
     * @param string $appkey The appkey
     *
     * @return array
     */
    public function getApp(string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
        }
        if ($appkey) {
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . 'apps/' . $this->appid;
        $this->method = 'GET';
        $this->fields = '';
        $this->setHeader();

        return $this->sendRequest()->getResponse('App');
    }

    /**
     * Creates an application in OneSignale.
     *
     * @see https://documentation.onesignal.com/reference#create-an-app
     *
     * @param array $bodyParams Body parameters
     *
     * @return array
     */
    public function createApp(array $bodyParams): array
    {
        $this->apiUrl = self::BASE_URL . 'apps';
        $this->method = 'POST';

        $this->setHeader();

        // Remove empty values
        $bodyParams = array_filter($bodyParams, function($value) {
            return $value !== '' && $value !== null && $value !== [];
        });

        $this->fields = json_encode($bodyParams) ?: '{}';

        return $this->sendRequest()->getResponse('App');
    }

    /**
     * Updates details of application in OneSignale.
     *
     * @see https://documentation.onesignal.com/reference#update-an-app
     *
     * @param array  $bodyParams Body parameters
     * @param string $appid      The appid
     *
     * @return array or app
     */
    public function updateApp(array $bodyParams, string $appid = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
        }

        $this->apiUrl = self::BASE_URL . 'apps/' . $this->appid;
        $this->method = 'PUT';

        $this->setHeader();

        // Remove empty values
        $bodyParams = array_filter($bodyParams, function($value) {
            return $value !== '' && $value !== null && $value !== [];
        });

        $this->fields = json_encode($bodyParams) ?: '{}';

        return $this->sendRequest()->getResponse('App');
    }

    /**
     * View the details of multiple devices in one of your OneSignal apps.
     *
     * @see https://documentation.onesignal.com/reference#view-devices
     *
     * @param int    $limit  How many devices to return. Max is 300. Default is 300
     * @param int    $offset Result offset. Default is 0. Results are sorted by id;
     * @param string $appid  The app ID that you want to view devices from
     * @param string $appkey The app key
     *
     * @return array the devices
     */
    public function getDevices(int $limit = 300, int $offset = 0, string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . "players?app_id={$this->appid}";
        $this->apiUrl .= "&limit={$limit}&offset={$offset}";
        $this->method = 'GET';
        $this->setHeader($this->appid, $this->appkey);
        $this->fields = '';

        return $this->sendRequest()->getResponse();
    }

    /**
     * View the details of an existing device in one of your OneSignal apps.
     *
     * @see https://documentation.onesignal.com/reference#view-device
     *
     * @param string $id     Player's OneSignal ID
     * @param string $appid  Your app_id for this device
     * @param string $appkey The appkey
     *
     * @return array the device
     */
    public function getDevice(string $id, string $appid = '', string $appkey = ''): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }

        $this->apiUrl = self::BASE_URL . "players/{$id}";
        $this->apiUrl .= "?app_id={$this->appid}";

        $this->method = 'GET';
        $this->setHeader($this->appid, $this->appkey);
        $this->fields = '';

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Register a new device to one of your OneSignal apps.
     *
     * @see https://documentation.onesignal.com/reference#add-a-device
     *
     * @param array $fields The body params
     *
     * @return array the device
     */
    public function addDevice(array $fields): array
    {
        $this->apiUrl = self::BASE_URL . 'players/';
        $this->method = 'POST';

        if (!isset($fields['app_id']) || empty($fields['app_id'])) {
            $fields['app_id'] = $this->appid;
        }
        $this->fields = json_encode($fields) ?: '{}';
        $this->setHeader($this->appid, $this->appkey);

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Update an existing device in one of your OneSignal apps.
     *
     * @see https://documentation.onesignal.com/reference#edit-device
     *
     * @param string $id     The device's OneSignal ID
     * @param array  $fields The body params
     *
     * @return array the device
     */
    public function editDevice(string $id, array $fields): array
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}";
        $this->method = 'PUT';
        $this->fields = json_encode($fields) ?: '{}';
        $this->setHeader();

        return $this->sendRequest()->getResponse('Device');
    }

    /**
     * Update a device's session information.
     *
     * @see https://documentation.onesignal.com/reference#new-session
     *
     * @param string $id     Player's OneSignal ID
     * @param array  $fields body params array
     *
     * @return array
     */
    public function onSession(string $id, array $fields = []): array
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_session";
        $this->method = 'POST';
        $this->setHeader();
        $this->fields = json_encode($fields) ?: '{}';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Track a new purchase in your app.
     *
     * @see https://documentation.onesignal.com/reference#new-purchase
     *
     * @param string $id       Player's OneSignal ID
     * @param array  $purchase The purchase array
     * @param bool   $existing The existing
     *
     * @return array
     */
    public function onPurchase(string $id, array $purchase, bool $existing = false): array
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_purchase";
        $this->method = 'POST';
        $this->setHeader();

        $fields = ['purchase' => $purchase];
        if ($existing) {
            $fields['existing'] = true;
        }
        $this->fields = json_encode($fields) ?: '{}';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Update a device's session length upon app resuming.
     *
     * @see https://documentation.onesignal.com/reference#increment-session-length
     *
     * @param string $id          Player's OneSignal ID
     * @param int    $active_time The active time
     *
     * @return array
     */
    public function onFocus(string $id, int $active_time = 60): array
    {
        $this->apiUrl = self::BASE_URL . "players/{$id}/on_focus";
        $this->method = 'POST';
        $this->setHeader();

        $fields = [
            'state' => 'ping',
            'active_time' => $active_time,
        ];
        $this->fields = json_encode($fields) ?: '{}';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Generate a compressed CSV export of all of your current user data.
     *
     * @see https://documentation.onesignal.com/reference#csv-export
     *
     * @param string $appid        the app ID that you want to export devices from
     * @param string $appkey       The appkey
     * @param array  $extra_fields default: ['location', 'country', 'rooted']
     *
     * @return array the result from API, it contains csv download url
     */
    public function exportCSV(string $appid = '', string $appkey = '', array $extra_fields = ['location', 'country', 'rooted']): array
    {
        if ($appid) {
            $this->appid = $appid;
            $this->appkey = $appkey;
        }
        $this->setHeader($this->appid, $this->appkey);

        $this->apiUrl = self::BASE_URL . 'players/csv_export?app_id=' . $this->appid;
        $this->method = 'POST';

        if (!empty($extra_fields)) {
            $this->fields = json_encode($extra_fields) ?: '[]';
        } else {
            $this->fields = '';
        }

        return $this->sendRequest()->getResponse();
    }

    /**
     * Track when users open a notification.
     *
     * @see https://documentation.onesignal.com/reference#track-open
     *
     * @param string $id    Notification id
     * @param string $appid The appid
     *
     * @return array
     */
    public function trackOpen(string $id, string $appid = ''): array
    {
        if (!$appid) {
            $appid = $this->appid;
        }

        $this->apiUrl = self::BASE_URL . "notifications/{$id}";
        $this->method = 'PUT';
        $this->setHeader();
        $fields = [
            'app_id' => $appid,
            'opened' => true,
        ];
        $this->fields = json_encode($fields) ?: '{}';

        return $this->sendRequest()->getResponse();
    }

    /**
     * Sends a request to OneSignal Server.
     *
     * @param string $url    The url
     * @param string $method The method
     * @param array  $header The header
     * @param string $fields The fields
     *
     * @return self
     * 
     * @throws \RuntimeException if cURL extension is not available
     */
    public function sendRequest(
        string $url = '',
        string $method = '',
        array $header = [],
        string $fields = ''
    ): self {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('cURL extension is required');
        }

        if (empty($url)) {
            $url = $this->apiUrl;
        }

        if (empty($method) || !in_array($method, self::METHODS, true)) {
            $method = $this->method;
        }

        if (empty($header)) {
            $header = $this->header;
        }

        if (empty($fields)) {
            $fields = $this->fields;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method !== 'GET' && in_array($method, self::METHODS, true)) {
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

        $this->responseRaw = $response === false ? null : (string)$response;
        $this->response = $response === false ? null : json_decode($response, true);
        curl_close($ch);

        return $this;
    }

    /**
     * Gets the response from OneSignal API or model from raw response.
     *
     * @param string $model The model name ('App/Notification/Device')
     *                      default: will return response array after json_decode
     *
     * @return array the response
     */
    public function getResponse(string $model = ''): array
    {
        if ($this->response === null) {
            return [];
        }

        $class = __NAMESPACE__ . '\\Models\\' . $model;

        if (!empty($model)
            && !isset($this->response['error'])
            && is_array($this->response)
            && class_exists($class)
        ) {
            return ['model' => $class, 'data' => $this->response];
        }

        return $this->response;
    }

    /**
     * Get the last error number from cURL
     *
     * @return int
     */
    public function getLastErrno(): int
    {
        return $this->lastErrno;
    }

    /**
     * Get the last error message from cURL
     *
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * Get the last HTTP response code
     *
     * @return int
     */
    public function getLastHttpCode(): int
    {
        return $this->lastHttpCode;
    }

    /**
     * Get the raw response
     *
     * @return string|null
     */
    public function getResponseRaw(): ?string
    {
        return $this->responseRaw;
    }

    /**
     * Check if the last request was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->lastHttpCode >= 200 && $this->lastHttpCode < 300;
    }

    /**
     * Get error details from the response
     *
     * @return array|null
     */
    public function getErrors(): ?array
    {
        if ($this->response !== null && isset($this->response['errors'])) {
            return $this->response['errors'];
        }
        return null;
    }

    /**
     * Magic method to handle dynamic property access for backward compatibility
     *
     * @param string $name Property name
     * @return mixed
     */
    public function __get(string $name)
    {
        // Map old property names to new methods for backward compatibility
        $propertyMap = [
            'lastErrno' => 'getLastErrno',
            'lastError' => 'getLastError', 
            'lastHttpCode' => 'getLastHttpCode',
            'responseRaw' => 'getResponseRaw'
        ];

        if (isset($propertyMap[$name])) {
            return $this->{$propertyMap[$name]}();
        }

        trigger_error("Undefined property: {$name}", E_USER_NOTICE);
        return null;
    }
}