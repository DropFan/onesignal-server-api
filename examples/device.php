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
 * @version 0.1
 *
 * @link https://github.com/DropFan/onesignal-server-api
 */
require_once __DIR__ . '/../vendor/autoload.php';

use OneSignalApi\OneSignal;

$config = require 'config.php';

$api = new OneSignal($config);

$r = $api->getDevices(); // return array of devices
echo 'getDevices():' . PHP_EOL;
var_dump($r);

$device_id = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

// var_dump($r); // object of Device or array
echo '========================================================' . PHP_EOL;
$r = $api->getDevice($device_id);
echo 'getDevice():' . PHP_EOL;
var_dump($api->response); // array after json_decode()

$bodyParams = [
    'app_id' => '',         // require
    'device_type' => 0,  // require
                            // 0 = iOS
                            // 1 = ANDROID
                            // 2 = AMAZON
                            // 3 = WINDOWSPHONE (MPNS)
                            // 4 = CHROME APPS / EXTENSIONS
                            // 5 = CHROME WEB PUSH
                            // 6 = WINDOWSPHONE (WNS)
                            // 7 = SAFARI
                            // 8 = FIREFOX
                            // 9 = MACOS
    'identifier' => '',
    'language' => '',
    'timezone' => 0,
    'game_version' => '',
    'device_model' => '',
    'device_os' => '',
    'ad_id' => '',      // The ad id for the device's platform:
                        // Android = Advertising Id
                        // iOS = identifierForVendor
                        // WP8.0 = DeviceUniqueId
                        // WP8.1 = AdvertisingId
    'sdk' => '',
    'session_count' => 0,
    'tags' => [],
    'amount_spent' => '',
    'created_at' => 0,
    'playtime' => 0,
    'badge_count' => 0,
    'last_active' => 0,
    'notification_types' => '', // 1 = subscribed
                                // -2 = unsubscribed
    'test_type' => null,  // 1 = Development
                          // 2 = Ad-Hoc
    'long' => 0.0, // longitude
    'lat' => 0.0, // latitude
];
$body = array_merge($bodyParams, $r->bodyParams);
$tags = $body['tags'];
$tags['test_tag'] = 'add_by_tiger';
$body['tags'] = $tags;

$api->editDevice($device_id, $body);
echo 'editDevice():' . PHP_EOL;
var_dump($api->response);
echo '========================================================' . PHP_EOL;
$api->addDevice($body);
echo 'addDevice():' . PHP_EOL;
var_dump($api->response);
echo '========================================================' . PHP_EOL;
$api->onSession($device_id, $body);
echo 'onSession():' . PHP_EOL;
var_dump($api->response);
echo '========================================================' . PHP_EOL;
$api->onFocus($device_id, 3);
echo 'onFocus():' . PHP_EOL;
var_dump($api->response);
echo '========================================================' . PHP_EOL;
echo 'exportCSV():' . PHP_EOL;
$api->exportCSV();
var_dump($api->response);
