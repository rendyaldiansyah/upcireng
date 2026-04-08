<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            // Hero
            'hero_headline'    => Setting::getSetting('hero_headline',    'Pesan cireng favorit dengan mudah'),
            'hero_subheadline' => Setting::getSetting('hero_subheadline', 'Menu Pilihan'),
            'hero_description' => Setting::getSetting('hero_description', 'Sistem order modern dengan fitur checkout yang cepat. Pesanan langsung masuk ke admin kami, dan kamu bisa cek status kapan saja.'),

            // Operational
            'operational_start' => Setting::getSetting('operational_start', '09:00'),
            'operational_end'   => Setting::getSetting('operational_end',   '23:00'),

            // Store identity
            'store_name'      => Setting::getSetting('store_name',      'UP Cireng'),
            'store_phone'     => Setting::getSetting('store_phone',     '6285189014426'),
            'store_email'     => Setting::getSetting('store_email',     'upcireng@example.com'),
            'store_instagram' => Setting::getSetting('store_instagram', '@upcireng'),
            'store_address'   => Setting::getSetting('store_address',   'Purbalingga, Jawa Tengah'),

            // ★ Delivery / COD
            'store_lat'         => Setting::getSetting('store_lat',         ''),
            'store_lng'         => Setting::getSetting('store_lng',         ''),
            'cod_free_km'       => Setting::getSetting('cod_free_km',       '5'),
            'cod_extra_per_km'  => Setting::getSetting('cod_extra_per_km',  '5000'),

            // ★ QRIS
            'qris_image'        => Setting::getSetting('qris_image',        ''),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // Hero
            'hero_headline'    => ['required', 'string', 'max:120'],
            'hero_subheadline' => ['required', 'string', 'max:60'],
            'hero_description' => ['required', 'string', 'max:300'],

            // Operational hours
            'operational_start' => ['required', 'date_format:H:i'],
            'operational_end'   => ['required', 'date_format:H:i'],

            // Store identity
            'store_name'      => ['required', 'string', 'max:100'],
            'store_phone'     => ['required', 'string', 'max:30'],
            'store_email'     => ['required', 'email',  'max:150'],
            'store_instagram' => ['nullable', 'string', 'max:60'],
            'store_address'   => ['nullable', 'string', 'max:500'],

            // ★ Delivery / COD
            'store_lat'        => ['nullable', 'numeric', 'between:-90,90'],
            'store_lng'        => ['nullable', 'numeric', 'between:-180,180'],
            'cod_free_km'      => ['required', 'numeric', 'min:0', 'max:100'],
            'cod_extra_per_km' => ['required', 'numeric', 'min:0'],

            // ★ QRIS Upload
            'qris_image'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        // ★ Handle QRIS image upload
        if ($request->hasFile('qris_image')) {
            // Delete old QRIS image if exists
            $oldQris = Setting::getSetting('qris_image', '');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }

            // Store new QRIS image
            $qrisPath = $request->file('qris_image')->store('qris', 'public');
            Setting::setSetting('qris_image', $qrisPath);
        }

        // Persist all settings
        $keys = [
            'hero_headline', 'hero_subheadline', 'hero_description',
            'operational_start', 'operational_end',
            'store_name', 'store_phone', 'store_email', 'store_instagram', 'store_address',
            'store_lat', 'store_lng', 'cod_free_km', 'cod_extra_per_km',
        ];

        foreach ($keys as $key) {
            Setting::setSetting($key, $validated[$key] ?? '');
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
