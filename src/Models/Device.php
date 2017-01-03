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

namespace OneSignalApi\Models;

class Device extends BaseModel
{
    public $fields = [
        'identifier' => '',
        'session_count' => 0,
        'language' => '',
        'timezone' => 0,
        'game_version' => '',
        'device_os' => '',
        'device_type' => '',
        'device_model' => '',
        'ad_id' => null,
        'tags' => [],
        'last_active' => 0,
        'amount_spent' => 0.0,
        'created_at' => 0,
        'invalid_identifier' => false,
        'badge_count' => 0,
    ];

    /**
     * @see https://documentation.onesignal.com/reference#add-a-device
     * @see https://documentation.onesignal.com/reference#edit-device
     */
    public $bodyParams = [
        'app_id' => '',         // require
        'device_type' => null,  // require
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
}
