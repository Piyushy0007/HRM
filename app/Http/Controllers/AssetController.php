<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Get all assets
    public function index(Request $request) {
        $perPage = $request->get('per_page', 10);
        $assets = Asset::paginate($perPage);
        return response()->json($assets);
    }


    // Insert a new asset
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'model_number' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage_capacity' => 'required|string|max:255',
            'imei_number' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:255',
            'previous_state' => 'required|string|max:255',
            'tag' => 'required|string|max:255',
        ]);

        $asset = Asset::create($validated);

        return response()->json($asset, 201);
    }
}
