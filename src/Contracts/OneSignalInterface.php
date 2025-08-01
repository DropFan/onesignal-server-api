<?php
/**
 * OneSignal API Interface for PHP 7+
 * 
 * @author    DropFan <DropFan@Gmail.com>
 * @copyright 2016 DropFan.
 * @license   MIT License
 */

declare(strict_types=1);

namespace OneSignalApi\Contracts;

interface OneSignalInterface
{
    /**
     * Create a notification
     * 
     * @param array $fields Notification fields
     * @return array
     */
    public function createNotification(array $fields): array;

    /**
     * Cancel a notification
     * 
     * @param string $id Notification ID
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function cancelNotification(string $id, string $appid = '', string $appkey = ''): array;

    /**
     * Get a single notification
     * 
     * @param string $id Notification ID
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function getNotification(string $id, string $appid = '', string $appkey = ''): array;

    /**
     * Get multiple notifications
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function getNotifications(int $limit = 50, int $offset = 0, string $appid = '', string $appkey = ''): array;

    /**
     * Get all apps
     * 
     * @return array
     */
    public function getApps(): array;

    /**
     * Get a single app
     * 
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function getApp(string $appid = '', string $appkey = ''): array;

    /**
     * Create an app
     * 
     * @param array $bodyParams App parameters
     * @return array
     */
    public function createApp(array $bodyParams): array;

    /**
     * Update an app
     * 
     * @param array $bodyParams App parameters
     * @param string $appid App ID
     * @return array
     */
    public function updateApp(array $bodyParams, string $appid = ''): array;

    /**
     * Get devices
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function getDevices(int $limit = 300, int $offset = 0, string $appid = '', string $appkey = ''): array;

    /**
     * Get a single device
     * 
     * @param string $id Player ID
     * @param string $appid App ID
     * @param string $appkey App key
     * @return array
     */
    public function getDevice(string $id, string $appid = '', string $appkey = ''): array;

    /**
     * Add a device
     * 
     * @param array $fields Device fields
     * @return array
     */
    public function addDevice(array $fields): array;

    /**
     * Edit a device
     * 
     * @param string $id Player ID
     * @param array $fields Device fields
     * @return array
     */
    public function editDevice(string $id, array $fields): array;

    /**
     * Update session
     * 
     * @param string $id Player ID
     * @param array $fields Session fields
     * @return array
     */
    public function onSession(string $id, array $fields = []): array;

    /**
     * Track purchase
     * 
     * @param string $id Player ID
     * @param array $purchase Purchase data
     * @param bool $existing Existing purchase
     * @return array
     */
    public function onPurchase(string $id, array $purchase, bool $existing = false): array;

    /**
     * Update focus time
     * 
     * @param string $id Player ID
     * @param int $active_time Active time in seconds
     * @return array
     */
    public function onFocus(string $id, int $active_time = 60): array;

    /**
     * Export CSV
     * 
     * @param string $appid App ID
     * @param string $appkey App key
     * @param array $extra_fields Extra fields to export
     * @return array
     */
    public function exportCSV(string $appid = '', string $appkey = '', array $extra_fields = ['location', 'country', 'rooted']): array;

    /**
     * Track notification open
     * 
     * @param string $id Notification ID
     * @param string $appid App ID
     * @return array
     */
    public function trackOpen(string $id, string $appid = ''): array;
}