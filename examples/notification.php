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

$headings = [
    'en' => 'english title',
    'zh-Hans' => '简体中文标题',
    'zh-Hant' => '繁體中文標題',
];

$contents = [
    'en' => 'Just 4 test | english content.',
    'zh-Hans' => 'Just 4 test | 简体中文 content',
    'zh-Hant' => 'Just 4 test | 繁體中文 content',
];

$subtitle = [
    'en' => 'english subtitle',
    'zh-Hans' => '简体中文 subtitle',
    'zh-Hant' => '繁體中文 subtitle',
];

$filters = [

];

$notification = [
    'app_id' => '',
    'app_ids' => [], // only iOS or Android. Required User Auth Key

    'headings' => $headings,
    'contents' => $contents,
    'subtitle' => $subtitle,

    'data' => [],

    // 'template_id' => '',

    // 'content_available' => false, // only iOS
    // 'mutable_content' => false, // only iOS 10+

    'included_segments' => ['All', 'Tiger'],
    'excluded_segments' => ['test users'],
    'filters' => $filters,

    'send_after' => '2016-12-27 22:30:00 GMT+0800',

    'ios_badgeType' => 'Increase',
    'ios_badgeCount' => 1,
];

$r = $api->createNotification($notification);

var_dump($api->response);
