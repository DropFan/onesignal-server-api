<?php
/**
 * Validation Trait for OneSignal API
 * 
 * @author    DropFan <DropFan@Gmail.com>
 * @copyright 2016 DropFan.
 * @license   MIT License
 */

declare(strict_types=1);

namespace OneSignalApi\Traits;

/**
 * Trait for validating OneSignal API parameters
 */
trait ValidationTrait
{
    /**
     * Validate notification fields
     * 
     * @param array $fields Notification fields
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function validateNotificationFields(array $fields): void
    {
        // Contents is required
        if (!isset($fields['contents']) || empty($fields['contents'])) {
            throw new \InvalidArgumentException('Notification contents are required');
        }

        // Validate contents structure
        if (!is_array($fields['contents'])) {
            throw new \InvalidArgumentException('Contents must be an array with language codes as keys');
        }

        // At least one targeting parameter is required
        $targetingParams = ['included_segments', 'excluded_segments', 'include_player_ids', 
                           'include_external_user_ids', 'filters', 'include_aliases'];
        
        $hasTargeting = false;
        foreach ($targetingParams as $param) {
            if (isset($fields[$param]) && !empty($fields[$param])) {
                $hasTargeting = true;
                break;
            }
        }

        if (!$hasTargeting && !isset($fields['app_ids'])) {
            throw new \InvalidArgumentException('At least one targeting parameter is required');
        }

        // Validate scheduled time if present
        if (isset($fields['send_after'])) {
            $sendTime = strtotime($fields['send_after']);
            if ($sendTime === false) {
                throw new \InvalidArgumentException('Invalid send_after format');
            }
            if ($sendTime < time()) {
                throw new \InvalidArgumentException('send_after must be in the future');
            }
        }

        // Validate URL formats
        $urlFields = ['url', 'web_url', 'app_url', 'big_picture', 'chrome_web_image'];
        foreach ($urlFields as $urlField) {
            if (isset($fields[$urlField]) && !empty($fields[$urlField])) {
                if (!filter_var($fields[$urlField], FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException("Invalid URL format for {$urlField}");
                }
            }
        }

        // Validate priority
        if (isset($fields['priority']) && ($fields['priority'] < 1 || $fields['priority'] > 10)) {
            throw new \InvalidArgumentException('Priority must be between 1 and 10');
        }
    }

    /**
     * Validate device fields
     * 
     * @param array $fields Device fields
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function validateDeviceFields(array $fields): void
    {
        // Device type is required for new devices
        if (isset($fields['device_type'])) {
            $validTypes = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
            if (!in_array($fields['device_type'], $validTypes, true)) {
                throw new \InvalidArgumentException('Invalid device_type value');
            }
        }

        // Validate language code format
        if (isset($fields['language']) && !empty($fields['language'])) {
            if (!preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $fields['language'])) {
                throw new \InvalidArgumentException('Invalid language code format');
            }
        }

        // Validate timezone offset
        if (isset($fields['timezone'])) {
            if ($fields['timezone'] < -43200 || $fields['timezone'] > 50400) {
                throw new \InvalidArgumentException('Invalid timezone offset');
            }
        }

        // Validate coordinates
        if (isset($fields['lat']) || isset($fields['long'])) {
            if (!isset($fields['lat']) || !isset($fields['long'])) {
                throw new \InvalidArgumentException('Both lat and long must be provided together');
            }
            if ($fields['lat'] < -90 || $fields['lat'] > 90) {
                throw new \InvalidArgumentException('Latitude must be between -90 and 90');
            }
            if ($fields['long'] < -180 || $fields['long'] > 180) {
                throw new \InvalidArgumentException('Longitude must be between -180 and 180');
            }
        }
    }

    /**
     * Validate app fields
     * 
     * @param array $fields App fields
     * @throws \InvalidArgumentException
     * @return void
     */
    protected function validateAppFields(array $fields): void
    {
        // Name is required for new apps
        if (isset($fields['name']) && empty($fields['name'])) {
            throw new \InvalidArgumentException('App name cannot be empty');
        }

        // Validate URLs
        $urlFields = ['site_name', 'safari_site_origin', 'chrome_web_origin', 
                     'chrome_web_default_notification_icon', 'safari_icon_256_256'];
        
        foreach ($urlFields as $urlField) {
            if (isset($fields[$urlField]) && !empty($fields[$urlField])) {
                if (in_array($urlField, ['chrome_web_origin', 'safari_site_origin']) && 
                    !filter_var($fields[$urlField], FILTER_VALIDATE_URL)) {
                    throw new \InvalidArgumentException("Invalid URL format for {$urlField}");
                }
            }
        }

        // Validate APNS environment
        if (isset($fields['apns_env']) && !in_array($fields['apns_env'], ['sandbox', 'production'])) {
            throw new \InvalidArgumentException('apns_env must be either "sandbox" or "production"');
        }
    }

    /**
     * Validate UUID format
     * 
     * @param string $uuid UUID to validate
     * @return bool
     */
    protected function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    /**
     * Sanitize input data
     * 
     * @param mixed $data Data to sanitize
     * @return mixed
     */
    protected function sanitize($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return $data;
    }
}