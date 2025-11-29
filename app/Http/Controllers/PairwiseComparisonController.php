<?php

namespace App\Http\Controllers;

use App\Models\PairwiseComparison;
use App\Models\Criteria;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PairwiseComparisonController extends Controller
{
    protected $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    /**
     * Tampilkan semua perbandingan berpasangan
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $comparisons = PairwiseComparison::where('user_id', $userId)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $comparisons,
        ]);
    }

    /**
     * Simpan perbandingan berpasangan (bulk)
     */
    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'comparisons' => 'required|array',
            'comparisons.*.criteria_i' => 'required|exists:criteria,id',
            'comparisons.*.criteria_j' => 'required|exists:criteria,id',
            'comparisons.*.value' => 'required|numeric',
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;

        // Hapus data lama user ini
        PairwiseComparison::where('user_id', $userId)->delete();

        // Simpan data baru
        foreach ($validated['comparisons'] as $comparison) {
            $comparison['user_id'] = $userId;
            PairwiseComparison::create($comparison);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pairwise comparisons saved successfully',
        ], 201);
    }

    /**
     * Simpan perbandingan dari matriks penuh
     */
    public function storeMatrix(Request $request)
    {
        $validated = $request->validate([
            'matrix' => 'required|array',
        ]);

        $criteria = Criteria::orderBy('id')->get();
        $n = $criteria->count();
        $matrix = $validated['matrix'];
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;

        // Validasi ukuran matriks
        if (count($matrix) !== $n) {
            return response()->json([
                'success' => false,
                'message' => 'Matrix size must be ' . $n . 'x' . $n,
            ], 400);
        }

        // Hapus data lama user ini
        PairwiseComparison::where('user_id', $userId)->delete();

        // Simpan matriks (hanya upper triangle, karena sisanya reciprocal)
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i; $j < $n; $j++) {
                if ($i === $j) continue; // Skip diagonal
                
                PairwiseComparison::create([
                    'user_id' => $userId,
                    'criteria_i' => $criteria[$i]->id,
                    'criteria_j' => $criteria[$j]->id,
                    'value' => $matrix[$i][$j],
                ]);
            }
        }

        // Calculate consistency immediately and save result
        $calculationResult = $this->ahpService->processAHP($userId);

        return response()->json([
            'success' => true,
            'message' => 'Pairwise comparison matrix saved and calculated successfully',
            'data' => [
                'consistency_ratio' => $calculationResult['cr'],
                'consistency_index' => $calculationResult['ci'],
                'random_index' => $calculationResult['ri'],
                'is_consistent' => $calculationResult['is_consistent'],
            ]
        ], 201);
    }
}
