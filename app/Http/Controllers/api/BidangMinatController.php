<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BidangMinatModel;
use Illuminate\Http\Request;

class BidangMinatController extends Controller
{
    public function index()
    {
        try {
            $bidangMinat = BidangMinatModel::all(); // Ambil semua data vendor
            return response()->json([
                'success' => true,
                'data' => $bidangMinat,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bidangMinat: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $bidangMinat = BidangMinatModel::create($request->all());
        return response()->json($bidangMinat, 201);
    }
    public function show(BidangMinatModel $bidangMinat)
    {
        return BidangMinatModel::find($bidangMinat);
    }
    public function update(Request $request, BidangMinatModel $bidangMinat)
    {
        $bidangMinat->update($request->all());
        return BidangMinatModel::find($bidangMinat);
    }
    public function destroy(BidangMinatModel $bidangMinat)
    {
        $bidangMinat->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
