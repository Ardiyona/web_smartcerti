<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        try {
            $periode = PeriodeModel::all(); // Ambil semua data periode
            return response()->json([
                'success' => true,
                'data' => $periode,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching periode: ' . $e->getMessage(),
            ], 500);
        }
    }
}
