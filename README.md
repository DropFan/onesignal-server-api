# OneSignal-server-API

[![Latest Stable Version](https://poser.pugx.org/dropfan/onesignal-server-api/v/stable)](https://packagist.org/packages/dropfan/onesignal-server-api)
[![Total Downloads](https://poser.pugx.org/dropfan/onesignal-server-api/downloads)](https://packagist.org/packages/dropfan/onesignal-server-api)
[![Latest Unstable Version](https://poser.pugx.org/dropfan/onesignal-server-api/v/unstable)](https://packagist.org/packages/dropfan/onesignal-server-api)
[![License](https://poser.pugx.org/dropfan/onesignal-server-api/license)](https://packagist.org/packages/dropfan/onesignal-server-api)
[![composer.lock](https://poser.pugx.org/dropfan/onesignal-server-api/composerlock)](https://packagist.org/packages/dropfan/onesignal-server-api)
[![StyleCI](https://styleci.io/repos/77390759/shield?branch=master)](https://styleci.io/repos/77390759)

OneSignal server API for PHP, you can push notifications to any platform. (iOS/APNS, Android/GCM/FCM, WP, Web/Chrome/Safari...etc.)
 
No third-party dependency that you can use this library in any project or framework.

There are several packages for OneSignal, but some rely on third-party library or certain framework components. That's reason of this project.

I will complete these when I have enough time.

##### TODO:
* OneSignal API wrapper (80%) // not fully test
	* Notifications (100%)
		* create
		* view 
		* update
	* Devices (100%)
		* add
		* edit
		* view 
	* Apps (100%)
		* create
		* view
		* update 
	* Other API (100%)
		* on_session
		* on_purchase
		* on_focus
		* csv_export 
		* track open
	* request (via curl) (100%)
	* request (via fsock) (0%)   // for non-blocking request
* Models	(50%) // just finish fields and body params, no ORM-style operation)
	* BaseModel
	* Notification
	* Device
	* App
* ORM-style (1%)
* PSR-2、PSR-4 (100%)
* Documents & Comments (about 60%~80%?)
* PHPUnit (0%)
* submit to packagist (100%)
* release 1.0 (coming soon...)
* release 2.0 // will support ORM operation

## Installation:

install via composer:

```composer require dropfan/onesignal-server-api```

Of course you can `clone` this project manually then move it into your projects.

```git clone https://github.com/DropFan/onesignal-server-api.git```

But for simple use，pass parameters by array rather than Object/Class (like ORM), you can move OneSignal.php into your projects only.

Surely, the ORM-style have not been finish. I will finish it later when I have enough time。

## Usage:
There are some samples in `examples` dir.

[https://github.com/DropFan/onesignal-server-api/tree/master/examples](https://github.com/DropFan/onesignal-server-api/tree/master/examples)

You can follow the offical ducumention from OneSignal.

[OneSignal API Documention](https://documentation.onesignal.com/reference)

## Contacts:
Author: Tiger(DropFan)

Email: <DropFan@Gmail.com>

Wechat: DropFan

Telegram: [DropFan](https://telegram.me/DropFan)

[https://about.me/DropFan](https://about.me/DropFan)

## LICENSE:
[MIT License](https://github.com/DropFan/onesignal-server-api/tree/master/LICENSE)
