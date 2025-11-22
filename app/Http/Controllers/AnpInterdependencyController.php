<?php

namespace App\Http\Controllers;

use App\Models\AnpInterdependency;
use App\Models\Criteria;
use Illuminate\Http\Request;

class AnpInterdependencyController extends Controller
{
    /**
     * Tampilkan semua interdependensi ANP
     */
    public function index()
    {
        $interdependencies = AnpInterdependency::with(['criteriaI', 'criteriaJ'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $interdependencies,
        ]);
    }

    /**
     * Simpan matriks interdependensi ANP
     */
    public function storeMatrix(Request $request)
    {
        $validated = $request->validate([
            'matrix' => 'required|array',
        ]);

        $criteria = Criteria::orderBy('id')->get();
        $n = $criteria->count();
        $matrix = $validated['matrix'];

        // Validasi ukuran matriks
        if (count($matrix) !== $n) {
            return response()->json([
                'success' => false,
                'message' => 'Matrix size must be ' . $n . 'x' . $n,
            ], 400);
        }

        // Hapus data lama
        AnpInterdependency::truncate();

        // Simpan seluruh matriks
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                AnpInterdependency::create([
                    'criteria_i' => $criteria[$i]->id,
                    'criteria_j' => $criteria[$j]->id,
                    'value' => $matrix[$i][$j],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'ANP interdependency matrix saved successfully',
        ], 201);
    }
}
