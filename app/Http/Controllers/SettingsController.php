<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    private function checkAdmin(): void
    {
        if (!Session::has('admin_id')) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $profile = Setting::storeProfile();
        $hours = Setting::getOperatingHours();

        $settings = [
            'operational_start' => $hours['start'],
            'operational_end' => $hours['end'],
            'store_name' => $profile['name'],
            'store_phone' => $profile['phone'],
            'store_email' => $profile['email'],
            'store_instagram' => $profile['instagram'],
            'store_address' => $profile['address'],
        ];

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'operational_start' => 'required|date_format:H:i',
            'operational_end' => 'required|date_format:H:i',
            'store_name' => 'required|string|max:100',
            'store_phone' => 'required|string|max:25',
            'store_email' => 'required|email|max:100',
            'store_instagram' => 'nullable|string|max:100',
            'store_address' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setSetting($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
