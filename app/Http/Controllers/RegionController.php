<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Get list of provinces.
     */
    public function provinces(Request $request): JsonResponse
    {
        $query = Province::query()->orderBy('name');

        if ($request->filled('q')) {
            $query->where('name', 'ilike', '%'.$request->query('q').'%');
        }

        $provinces = $query->get(['id', 'code', 'name']);

        return response()->json($provinces);
    }

    /**
     * Get list of cities / regencies (Kota & Kabupaten).
     */
    public function cities(Request $request): JsonResponse
    {
        $query = City::with('province:id,name')->orderBy('name');

        if ($request->filled('province_id')) {
            $query->where('province_id', (int) $request->query('province_id'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where('name', 'ilike', "%{$search}%");
        }

        $cities = $query->get()->map(function (City $city): array {
            return [
                'id' => $city->id,
                'province_id' => $city->province_id,
                'province_name' => $city->province?->name,
                'type' => $city->type,
                'name' => $city->name,
                'full_name' => "{$city->type} {$city->name}",
                'latitude' => $city->latitude,
                'longitude' => $city->longitude,
            ];
        });

        return response()->json($cities);
    }

    /**
     * Get list of districts (Kecamatan) within a city.
     */
    public function districts(Request $request): JsonResponse
    {
        $query = District::query()->orderBy('name');

        if ($request->filled('city_id')) {
            $query->where('city_id', (int) $request->query('city_id'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where('name', 'ilike', "%{$search}%");
        }

        $districts = $query->get(['id', 'city_id', 'name', 'latitude', 'longitude']);

        return response()->json($districts);
    }

    /**
     * Get list of villages (Kelurahan / Desa) within a district.
     */
    public function villages(Request $request): JsonResponse
    {
        $query = Village::query()->orderBy('name');

        if ($request->filled('district_id')) {
            $query->where('district_id', (int) $request->query('district_id'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where('name', 'ilike', "%{$search}%");
        }

        $villages = $query->get(['id', 'district_id', 'name', 'postal_code', 'latitude', 'longitude']);

        return response()->json($villages);
    }
}
