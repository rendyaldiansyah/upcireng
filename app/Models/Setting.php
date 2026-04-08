<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    public static function getSetting(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setSetting(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getOperatingHours(): array
    {
        return [
            'start' => self::getSetting('operational_start', '09:00'),
            'end'   => self::getSetting('operational_end', '23:00'),
        ];
    }

    public static function isStoreOpen(?Carbon $now = null): bool
    {
        $now ??= now('Asia/Jakarta');
        $hours = self::getOperatingHours();

        $start = Carbon::createFromFormat('H:i', $hours['start'], 'Asia/Jakarta')->setDateFrom($now);
        $end   = Carbon::createFromFormat('H:i', $hours['end'],   'Asia/Jakarta')->setDateFrom($now);

        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }

        return $now->betweenIncluded($start, $end->copy()->subMinute());
    }

    public static function storeProfile(): array
    {
        return [
            'name'      => self::getSetting('store_name',      'UP Cireng'),
            'phone'     => self::getSetting('store_phone',     '6285189014426'),
            'email'     => self::getSetting('store_email',     'upcireng@example.com'),
            'instagram' => self::getSetting('store_instagram', '@upcireng'),
            'address'   => self::getSetting('store_address',   'Purbalingga, Jawa Tengah'),
        ];
    }

    /**
     * Hero section content shown on storefront homepage.
     *
     * @return array{headline: string, subheadline: string, description: string}
     */
    public static function heroContent(): array
    {
        return [
            'headline'    => self::getSetting('hero_headline',    'Pesan cireng favorit dengan mudah'),
            'subheadline' => self::getSetting('hero_subheadline', 'Menu Pilihan'),
            'description' => self::getSetting('hero_description', 'Sistem order modern dengan fitur checkout yang cepat. Pesanan langsung masuk ke admin kami, dan kamu bisa cek status kapan saja.'),
        ];
    }
}