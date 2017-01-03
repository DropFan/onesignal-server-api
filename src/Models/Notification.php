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

class Notification extends BaseModel
{
    /**
     * @see https://documentation.onesignal.com/reference#view-notification
     * @see https://documentation.onesignal.com/reference#view-notifications
     */
    public $fields = [
        'id' => '',
        'successful' => 0,
        'failed' => 0,
        'converted' => 0,
        'remaining' => 0,
        'queued_at' => 0,
        'send_after' => 0,
        'url' => '',
        'data' => [],
        'canceled' => false,

        'headings' => [],
        'contents' => [],
    ];

    /**
     * @see https://documentation.onesignal.com/reference#create-notification
     */
    public $bodyParams = [

        // App
        'app_id' => '',     // REQUIRED Your OneSignal application ID
        'app_ids' => [],    // only iOS or Android. Required User Auth Key

        // start contents & language
        'headings' => [],   // The notification's title, a map of language codes to text for each language.
                            // This field supports inline substitutions.
                            // @see https://documentation.onesignal.com/docs/notification-content#section-notification-content-substitution
        'contents' => [],   // REQUIRED unless content_available=true or template_id is set.
                            // The notification's content (excluding the title)
                            // This field supports inline substitutions.
                            // @see https://documentation.onesignal.com/docs/notification-content#section-notification-content-substitution
        'subtitle' => [],   // only iOS 10+
                            // The notification's subtitle,
                            // This field supports inline substitutions.
                            // @see https://documentation.onesignal.com/docs/notification-content#section-notification-content-substitution

        'template_id' => '',    // Use a template you setup on our dashboard.
                                // You can override the template values by sending other parameters with the request.
                                // The template_id is the UUID found in the URL when viewing a template on our dashboard.

        'content_available' => false, // only iOS
                                      // Sending true wakes your app to run custom native code (Apple interprets this as content-available=1). Omit contents field to make notification silent.
        'mutable_content' => false, // only iOS 10+
                                    // Sending true allows you to change the notification content in your app before it is displayed. Triggers didReceive(_:withContentHandler:) on your UNNotificationServiceExtension.
        // end contents & language

        // segments
        'included_segments' => [],
        'excluded_segments' => [],

        // Attachments, buttons, Appearance, delivery, group_collapse
        // will merge $this->attachments/ $this->buttons/ $this->appearance / $this->delivery / $this->group_collapse

        'filters' => [],
    ];

    public $headings = [
        'en' => 'english title',
        'zh' => 'chinese title',
    ];

    // subtitle only iOS 10+
    public $subtitle = [
        'en' => 'english subtitle',
        'zh' => 'chinese subtitle',
    ];

    // REQUIRED unless content_available=true or template_id is set.
    public $contents = [
        'en' => 'english content',
        'zh' => 'chinese content',
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-attachments
     */
    public $attachments = [
        'data' => [],
        'url' => '',            // This field supports inline substitutions.
        'ios_attachments' => [],    // only iOS 10+
        'big_picture' => '',        // only Android
        'adm_big_picture' => '',    // only amazon
        'chrome_big_picture' => '', // only Chrome App
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-action-buttons
     */
    public $buttons = [
        'buttons' => [],    // iOS 8.0+, ANDROID 4.1+ (and derivatives like AMAZON)
                            // Buttons to add to the notification. Icon only works for Android.
                            // Example:
                            //  [{
                            //      "id": "id1",
                            //      "text": "button1",
                            //      "icon": "ic_menu_share"
                            //   },{
                            //     "id": "id2",
                            //     "text": "button2",
                            //     "icon": "ic_menu_send"
                            //  }]
        'web_buttons' => [],    // CHROME 48+
                                // Add action buttons to the notification. The id field is required.
                                // Example:
                                // [{
                                //      "id": "like-button",
                                //      "text": "Like",
                                //      "icon": "http://i.imgur.com/N8SN8ZS.png",
                                //      "url": "https://yoursite.com"
                                //  }, {
                                //      "id": "read-more-button",
                                //      "text": "Read more",
                                //      "icon": "http://i.imgur.com/MIxJp1L.png",
                                //      "url": "https://yoursite.com"
                                // }]
        'ios_category' => '',   // iOS
                                // Category APS payload, use with registerUserNotificationSettings:categories in your Objective-C / Swift code.
                                // Example: calendar category which contains actions like accept and decline
                                // iOS 10+ This will trigger your UNNotificationContentExtension whose ID matches this category.
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-appearance
     */
    public $appearance = [
        'android_background_layout' => [], // ANDROID
                                           //
        'small_icon' => '',             // ANDROID
        'large_icon' => '',             // ANDROID
        'chrome_web_icon' => '',        // CHROME
        'firefox_icon' => '',           // FIREFOX
        'adm_small_icon' => '',         // ANDROID
        'adm_large_icon' => '',         // ANDROID
        'chrome_icon' => '',            //
        'ios_sound' => '',              // iOS
        'android_sound' => '',          // ANDROID
        'adm_sound' => '',              // AMAZON
        'wp_sound' => '',               // WINDOWS 8.0
        'wp_wns_sound' => '',           // WINDOWS 8.1
        'android_led_color' => '',      // ANDROID
        'android_accent_color' => '',   // ANDROID
        'android_visibility' => 0,      // ANDROID 5.0+
        'ios_badgeType' => '',          // iOS
                                        // Describes whether to set or increase/decrease your app's iOS badge count by the ios_badgeCount specified count.
                                        // Can specify None, SetTo, or Increase.
                                        // None leaves the count unaffected.
                                        // SetTo directly sets the badge count to the number specified in ios_badgeCount.
                                        // Increase adds the number specified in ios_badgeCount to the total.
                                        //      Use a negative number to decrease the badge count.
        'ios_badgeCount' => '',         // iOS
                                        // Used with ios_badgeType, describes the value to set or amount to increase/decrease your app's iOS badge count by.
                                        // You can use a negative number to decrease the badge count when used with an ios_badgeType of Increase.
        'collapse_id' => '',            // iOS 10+ ANDROID
                                        // Only one notification with the same id will be shown on the device.
                                        // Use the same id to update an existing notification instead of showing a new one.
                                        // This is known as apns-collapse-id on iOS and collapse_key on Android.
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-delivery
     */
    public $delivery = [
        'send_after' => '',         // Schedule notification for future delivery.
                                    // Examples: All examples are the exact same date & time.
                                    // "Thu Sep 24 2015 14:00:00 GMT-0700 (PDT)"
                                    // "September 24th 2015, 2:00:00 pm UTC-07:00"
                                    // "2015-09-24 14:00:00 GMT-0700"
                                    // "Sept 24 2015 14:00:00 GMT-0700"
                                    // "Thu Sep 24 2015 14:00:00 GMT-0700 (Pacific Daylight Time)"
        'delayed_option' => '',     // Possible values are:
                                    // timezone (Deliver at a specific time-of-day in each users own timezone)
                                    // last-active (Deliver at the same time of day as each user last used your app).
                                    // If send_after is used, this takes effect after the send_after time has elapsed.
        'delivery_time_of_day' => '', // Use with delayed_option=timezone. Example: "9:00AM"
        'ttl' => '', // iOS, ANDROID, CHROME, CHROMEWEB
                     // Time To Live - In seconds.
                     // The notification will be expired if the device does not come back online within this time.
                     // The default is 259,200 seconds (3 days).
        'priority' => '', // iOS, ANDROID, CHROME, CHROMEWEB
                          // Delivery priority through the push server (example GCM/FCM).
                          // Pass 10 for high priority. Defaults to normal priority for Android and high for iOS.
                          // For Android 6.0+ devices setting priority to high will wake the device out of doze mode.
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-grouping-collapsing
     */
    public $group_collapse = [
        'android_group' => '',          // ANDROID
        'android_group_message' => [],  // ANDROID
        'adm_group' => '',              // AMAZON
        'adm_group_message' => [],      // AMAZON
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-platform-to-deliver-to
     * By default, OneSignal will send to every platform (each of these is true).
     */
    public $platform = [
        'isIos' => true,
        'isAndroid' => true,
        'isAnyWeb' => true,
        'isChromeWeb' => true,
        'isFirefox' => true,
        'isSafari' => true,
        'isWP' => true,
        'isWP_WNS' => true,
        'isAdm' => true,
        'isChrome' => true,
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-send-to-segments
     */
    public $segments = [
        'included_segments' => [],  // REQUIRED The segment names you want to target.
                                    // Users in these segments will receive a notification.
                                    // This targeting parameter is only compatible with excluded_segments.
                                    // Example: ["Active Users", "Inactive Users"]
        'excluded_segments' => [],  // Segment that will be excluded when sending.
                                    // Users in these segments will not receive a notification,
                                    //      even if they were included in included_segments.
                                    // This targeting parameter is only compatible with included_segments.
                                    // Example: ["Active Users", "Inactive Users"]
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-send-to-users-based-on-filters
     */
    public $filters = [
        'last_session' => [],   // relation = ">" or "<"
                                // hours_ago = number of hours before or after the users last session.
                                //          Example: "1.1"
        'first_session' => [],  // relation = ">" or "<"
                                // hours_ago = number of hours before or after the users first session.
                                //          Example: "1.1"
        'session_count' => [],  // relation = ">", "<", "=" or "!="
                                // value = number sessions. Example: "1"
        'session_time' => [],   // relation = ">" or "<"
                                // value = Time in seconds the user has been in your app.
                                //         Example: "3600"
        'amount_spent' => [],   // relation = ">", "<", or "="
                                // value = Amount in USD a user has spent on IAP (In App Purchases).
                                //       Example: "0.99"
        'bought_sku' => [],     // relation = ">", "<" or "="
                                // key = SKU purchased in your app as an IAP (In App Purchases).
                                //      Example: "com.domain.100coinpack"
                                // value = value of SKU to compare to. Example: "0.99"
        'tag' => [],            // relation = ">", "<", "=", "!=", "exists" or "not_exists"
                                // key = Tag key to compare.
                                // value = Tag value to compare. Not required for "exists" or "not_exists"
                                // @see https://documentation.onesignal.com/reference#section-formatting-filters
        'language' => [],       // relation = "=" or "!="
                                // value = 2 character language code. Example: "en"
                                // @see https://documentation.onesignal.com/docs/language-localization
        'app_version' => [],    // relation = ">", "<", "=" or "!="
                                // value = app version. Example: "1.0.0"
        'location' => [],       // radius = in meters
                                // lat = latitude
                                // long = longitude
        'email' => [],          // value = email address
    ];

    /**
     * @see https://documentation.onesignal.com/reference#section-send-to-specific-devices
     */
    public $specific_devices = [
        'include_player_ids' => [],     // RECOMMENDED
                                        // Specific players to send your notification to.
                                        // Does not require API Auth Key.
                                        // Do not combine with other targeting parameters.
                                        // Not compatible with any other targeting parameters.
                                        // Example: ["1dd608f2-c6a1-11e3-851d-000c2940e62c"]
                                        // Limit of 2,000 entries per REST API call
        'include_ios_tokens' => [],     // NOT RECOMMENDED - Please consider using include_player_ids instead.
                                        // Target using iOS device tokens.
                                        // Warning: Only works with Production tokens.
                                        // All non-alphanumeric characters must be removed from each token.
                                        // If a token does not correspond to an existing user, a new user will be created. Example: ce777617da7f548fe7a9ab6febb56cf39fba6d38203...
        'include_wp_urls' => [],
        'include_wp_wns_uris' => [],
        'include_amazon_reg_ids' => [],
        'include_chrome_reg_ids' => [],
        'include_chrome_web_reg_ids' => [],
        'include_android_reg_ids' => [],
    ];

    public function __construct(array $fields)
    {
        $this->bodyParams = array_merge(
            $this->bodyParams,
            $this->attachments,
            $this->buttons,
            $this->appearance,
            $this->delivery,
            $this->group_collapse,
            $this->platform
        );
        parent::__construct($fields);
    }
}
