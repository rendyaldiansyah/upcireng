<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    /**
     * POST /api/check-distance
     *
     * Geocode customer address via OpenStreetMap Nominatim (FREE, no API key)
     * then calculate Haversine distance from store.
     * Returns distance_km, delivery_fee, within_coverage.
     */
    public function checkDistance(Request $request): JsonResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        // 1. Load store coordinates from settings
        $storeLat = (float) Setting::getSetting('store_lat');
        $storeLng = (float) Setting::getSetting('store_lng');

        if (empty($storeLat) || empty($storeLng)) {
            return response()->json([
                'error'   => true,
                'message' => 'Koordinat toko belum dikonfigurasi. Hubungi admin.',
            ], 422);
        }

        $codFreeKm     = (float) Setting::getSetting('cod_free_km', 5);
        $codExtraPerKm = (float) Setting::getSetting('cod_extra_per_km', 5000);

        // 2. Geocode customer address using Nominatim (free, no key)
        $address = trim($request->input('address'));
        $coords  = $this->geocodeAddress($address);

        if (!$coords) {
            return response()->json([
                'error'   => true,
                'message' => 'Alamat tidak ditemukan. Coba tulis lebih lengkap, contoh: Jl. Merdeka No.1, Kelurahan, Kota.',
            ], 422);
        }

        // 3. Calculate straight-line distance (Haversine)
        $distanceKm = $this->haversine($storeLat, $storeLng, $coords['lat'], $coords['lng']);

        // 4. Calculate delivery fee
        $deliveryFee    = 0;
        $withinCoverage = $distanceKm <= $codFreeKm;

        if (!$withinCoverage) {
            $extraKm     = ceil($distanceKm - $codFreeKm);
            $deliveryFee = (int) ($extraKm * $codExtraPerKm);
        }

        return response()->json([
            'distance_km'      => round($distanceKm, 2),
            'delivery_fee'     => $deliveryFee,
            'within_coverage'  => $withinCoverage,
            'cod_free_km'      => $codFreeKm,
            'cod_extra_per_km' => $codExtraPerKm,
            'customer_coords'  => $coords,
            'display_name'     => $coords['display_name'] ?? $address,
        ]);
    }

    /**
     * POST /api/check-distance-coords
     *
     * Calculate distance using raw lat/lng from browser GPS.
     * No geocoding needed — instant and 100% reliable.
     * Also reverse-geocodes the coords to get a human-readable address.
     */
    public function checkDistanceByCoords(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        // 1. Load store coordinates
        $storeLat = (float) Setting::getSetting('store_lat');
        $storeLng = (float) Setting::getSetting('store_lng');

        if (empty($storeLat) || empty($storeLng)) {
            return response()->json([
                'error'   => true,
                'message' => 'Koordinat toko belum dikonfigurasi. Hubungi admin.',
            ], 422);
        }

        $codFreeKm     = (float) Setting::getSetting('cod_free_km', 5);
        $codExtraPerKm = (float) Setting::getSetting('cod_extra_per_km', 5000);

        $customerLat = (float) $request->input('lat');
        $customerLng = (float) $request->input('lng');

        // 2. Calculate distance instantly (no API call needed)
        $distanceKm = $this->haversine($storeLat, $storeLng, $customerLat, $customerLng);

        // 3. Calculate delivery fee
        $deliveryFee    = 0;
        $withinCoverage = $distanceKm <= $codFreeKm;

        if (!$withinCoverage) {
            $extraKm     = ceil($distanceKm - $codFreeKm);
            $deliveryFee = (int) ($extraKm * $codExtraPerKm);
        }

        // 4. Reverse geocode to get human-readable address (optional, best-effort)
        $displayName = $this->reverseGeocode($customerLat, $customerLng);

        return response()->json([
            'distance_km'      => round($distanceKm, 2),
            'delivery_fee'     => $deliveryFee,
            'within_coverage'  => $withinCoverage,
            'cod_free_km'      => $codFreeKm,
            'cod_extra_per_km' => $codExtraPerKm,
            'customer_coords'  => ['lat' => $customerLat, 'lng' => $customerLng],
            'display_name'     => $displayName,
        ]);
    }

    /**
     * Geocode an address string using OpenStreetMap Nominatim.
     */
    private function geocodeAddress(string $address): ?array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'UPCirengOrderSystem/1.0 (upcireng@upcireng.my.id)',
                'Accept'     => 'application/json',
                'Referer'    => config('app.url'),
            ])->timeout(8)->get('https://nominatim.openstreetmap.org/search', [
                'q'              => $address,
                'format'         => 'json',
                'limit'          => 1,
                'addressdetails' => 1,
                'countrycodes'   => 'id',
            ]);

            if (!$response->ok()) {
                Log::warning('Nominatim API error', ['status' => $response->status()]);
                return null;
            }

            $results = $response->json();

            if (empty($results) || !isset($results[0]['lat'])) {
                return null;
            }

            return [
                'lat'          => (float) $results[0]['lat'],
                'lng'          => (float) $results[0]['lon'],
                'display_name' => $results[0]['display_name'] ?? $address,
            ];
        } catch (\Throwable $e) {
            Log::error('Geocoding failed', ['error' => $e->getMessage(), 'address' => $address]);
            return null;
        }
    }

    /**
     * Reverse geocode lat/lng to a human-readable address using Nominatim.
     * Returns display_name string or a fallback coordinate string.
     */
    private function reverseGeocode(float $lat, float $lng): string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'UPCirengOrderSystem/1.0 (upcireng@upcireng.my.id)',
                'Accept'     => 'application/json',
                'Referer'    => config('app.url'),
            ])->timeout(6)->get('https://nominatim.openstreetmap.org/reverse', [
                'lat'    => $lat,
                'lon'    => $lng,
                'format' => 'json',
            ]);

            if ($response->ok()) {
                $data = $response->json();
                return $data['display_name'] ?? "Koordinat: {$lat}, {$lng}";
            }
        } catch (\Throwable $e) {
            Log::warning('Reverse geocoding failed', ['error' => $e->getMessage()]);
        }

        return "Koordinat: {$lat}, {$lng}";
    }

    /**
     * Haversine formula — great-circle distance between two lat/lng points (km).
     */
    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}