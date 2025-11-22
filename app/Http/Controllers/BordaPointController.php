<?php

namespace App\Http\Controllers;

use App\Models\BordaPoint;
use App\Models\Alternative;
use App\Models\DecisionMaker;
use Illuminate\Http\Request;

class BordaPointController extends Controller
{
    /**
     * Tampilkan semua poin Borda
     */
    public function index()
    {
        $points = BordaPoint::with(['decisionMaker', 'alternative'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $points,
        ]);
    }

    /**
     * Simpan poin Borda (bulk)
     */
    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'points' => 'required|array',
            'points.*.decision_maker_id' => 'required|exists:decision_makers,id',
            'points.*.alternative_id' => 'required|exists:alternatives,id',
            'points.*.points' => 'required|integer|min:1',
        ]);

        // Hapus data lama
        BordaPoint::truncate();

        // Simpan data baru
        foreach ($validated['points'] as $point) {
            BordaPoint::create($point);
        }

        return response()->json([
            'success' => true,
            'message' => 'Borda points saved successfully',
        ], 201);
    }

    /**
     * Simpan poin Borda dari matriks
     */
    public function storeMatrix(Request $request)
    {
        $validated = $request->validate([
            'matrix' => 'required|array',
        ]);

        $decisionMakers = DecisionMaker::orderBy('id')->get();
        $alternatives = Alternative::orderBy('id')->get();
        
        $numDM = $decisionMakers->count();
        $numAlt = $alternatives->count();
        $matrix = $validated['matrix'];

        // Validasi ukuran matriks
        if (count($matrix) !== $numDM) {
            return response()->json([
                'success' => false,
                'message' => 'Matrix size must be ' . $numDM . 'x' . $numAlt,
            ], 400);
        }

        // Hapus data lama
        BordaPoint::truncate();

        // Simpan matriks
        for ($k = 0; $k < $numDM; $k++) {
            for ($i = 0; $i < $numAlt; $i++) {
                BordaPoint::create([
                    'decision_maker_id' => $decisionMakers[$k]->id,
                    'alternative_id' => $alternatives[$i]->id,
                    'points' => $matrix[$k][$i],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Borda points matrix saved successfully',
        ], 201);
    }
}
