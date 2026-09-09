<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    private const PRIMARY_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';
    private const FALLBACK_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    private function fetchWithFallback(string $path)
    {
        try {
            $response = Http::timeout(5)->get(self::PRIMARY_URL . $path);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            // Coba fallback
        }

        try {
            $response = Http::timeout(5)->get(self::FALLBACK_URL . $path);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            // Fallback gagal
        }

        return null;
    }

    public function provinces()
    {
        $data = Cache::remember('indo_regions_provinces', 86400 * 30, function () {
            return $this->fetchWithFallback('/provinces.json');
        });

        if (!$data) {
            return response()->json(['error' => 'Gagal memuat data provinsi'], 500);
        }

        return response()->json($data);
    }

    public function regencies($provinceId)
    {
        $data = Cache::remember("indo_regions_regencies_{$provinceId}", 86400 * 30, function () use ($provinceId) {
            return $this->fetchWithFallback("/regencies/{$provinceId}.json");
        });

        if (!$data) {
            return response()->json(['error' => 'Gagal memuat data kabupaten/kota'], 500);
        }

        return response()->json($data);
    }

    public function districts($regencyId)
    {
        $data = Cache::remember("indo_regions_districts_{$regencyId}", 86400 * 30, function () use ($regencyId) {
            return $this->fetchWithFallback("/districts/{$regencyId}.json");
        });

        if (!$data) {
            return response()->json(['error' => 'Gagal memuat data kecamatan'], 500);
        }

        return response()->json($data);
    }

    public function villages($districtId)
    {
        $data = Cache::remember("indo_regions_villages_{$districtId}", 86400 * 30, function () use ($districtId) {
            return $this->fetchWithFallback("/villages/{$districtId}.json");
        });

        if (!$data) {
            return response()->json(['error' => 'Gagal memuat data desa/kelurahan'], 500);
        }

        return response()->json($data);
    }
}
