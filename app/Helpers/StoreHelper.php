<?php

namespace App\Helpers;

use App\Models\Setting;

class StoreHelper
{
    /**
     * Check if store is currently open
     */
    public static function isStoreOpen(): bool
    {
        return Setting::isStoreOpen();
    }

    /**
     * Get store operating hours
     *
     * @return array{start: string, end: string}
     */
    public static function getOperatingHours(): array
    {
        return Setting::getOperatingHours();
    }

    /**
     * Get store profile information
     *
     * @return array<string, string>
     */
    public static function storeProfile(): array
    {
        return Setting::storeProfile();
    }

    /**
     * Get single setting value
     */
    public static function getSetting(string $key, $default = null): mixed
    {
        return Setting::getSetting($key, $default);
    }

    /**
     * Set single setting value
     */
    public static function setSetting(string $key, $value): void
    {
        Setting::setSetting($key, $value);
    }
}
