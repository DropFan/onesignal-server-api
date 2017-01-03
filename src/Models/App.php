<?php
/**
 * MIT License
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

class App extends BaseModel
{
    /**
     * @see https://documentation.onesignal.com/reference#view-apps-apps
     * @see https://documentation.onesignal.com/reference#view-an-app
     */
    public $fields = [
        'id' => '',
        'name' => '',
        'basic_auth_key' => '',

        'gcm_key' => '',

        'chrome_key' => '',
        'chrome_web_key' => '',
        'chrome_web_origin' => '',
        'chrome_web_gcm_sender_id' => '',
        'chrome_web_default_notification_icon' => '',
        'chrome_web_sub_domain' => '',

        'apns_env' => '',
        'apns_certificates' => '',

        'safari_apns_certificate' => '',
        'safari_site_origin' => '',
        'safari_push_id' => '',
        'safari_icon_16_16' => '',
        'safari_icon_32_32' => '',
        'safari_icon_64_64' => '',
        'safari_icon_128_128' => '',
        'safari_icon_256_256' => '',

        'site_name' => '',

        'created_at' => '',
        'updated_at' => '',

        'players' => '',
        'messageable_players' => '',
    ];

    /**
     * @see https://documentation.onesignal.com/reference#create-an-app
     * @see https://documentation.onesignal.com/reference#update-an-app
     */
    public $bodyParams = [
        'name' => '',     // require
        'apns_env' => '',
        'apns_p12' => '',
        'apns_p12_password' => '',

        'gcm_key' => '',
        'android_gcm_sender_id' => '',

        'chrome_web_origin' => '',
        'chrome_web_default_notification_icon' => '',
        'chrome_web_sub_domain' => '',

        'safari_apns_p12' => '',
        'namsafari_apns_p12_passworde' => '',
        'site_name' => '',
        'nasafari_site_originme' => '',
        'safari_icon_16_16' => '',
        'safari_icon_32_32' => '',
        'safari_icon_64_64' => '',
        'safari_icon_128_128' => '',
        'safari_icon_256_256' => '',
        'chrome_key' => '',
    ];
}
