<?php

namespace App\Http\Controllers;

use App\Models\AnpInterdependency;
use App\Models\Criteria;
use App\Services\AnpService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnpInterdependencyController extends Controller
{
    protected $anpService;

    public function __construct(AnpService $anpService)
    {
        $this->anpService = $anpService;
    }

    /**
     * Tampilkan semua interdependensi ANP
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $interdependencies = AnpInterdependency::where('user_id', $userId)
            ->get();
        
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
        // mengambil jumlah kriteria
        $n = $criteria->count();
        // mengambil matriks dari request
        $matrix = $validated['matrix'];
        // mengambil identitas user yang melakukan request
        $user = JWTAuth::parseToken()->authenticate();
        // mengambil id user (DM)
        $userId = $user->id;

        // Validasi ukuran matriks harus berukuran 8x8 (sesuai jumlah kriteria)
        if (count($matrix) !== $n) {
            return response()->json([
                'success' => false,
                'message' => 'Matrix size must be ' . $n . 'x' . $n,
            ], 400);
        }

        // hapus data matrix yang lama
        AnpInterdependency::where('user_id', $userId)->delete();

        // Simpan seluruh matriks ke dalam ANP interdependency table
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                AnpInterdependency::create([
                    'user_id' => $userId,
                    'criteria_i' => $criteria[$i]->id,
                    'criteria_j' => $criteria[$j]->id,
                    'value' => $matrix[$i][$j],
                ]);
            }
        }

        // Melakukan pemrosesan ANP setelah matriks disimpan
        try {
            $this->anpService->processANP($userId);
        } catch (\Exception $e) {
            // If AHP is not found, we might want to warn the user but still save the matrix
            return response()->json([
                'success' => true,
                'message' => 'ANP matrix saved, but calculation failed: ' . $e->getMessage(),
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'ANP interdependency matrix saved and calculated successfully',
        ], 201);
    }
}
