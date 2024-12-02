<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorPelatihanModel;
use Illuminate\Support\Facades\Auth;

class VendorPelatihanController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::guard('api')->user();
            $vendors = VendorPelatihanModel::all(); // Ambil semua data vendor
            return response()->json([
                'success' => true,
                'data' => $vendors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching vendors: ' . $e->getMessage(),
            ], 500);
        }
    }
}
