<?php

namespace App\Http\Controllers;

use App\Models\AlternativeRating;
use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Http\Request;

class AlternativeRatingController extends Controller
{
    /**
     * Tampilkan semua rating
     */
    public function index()
    {
        $ratings = AlternativeRating::with(['alternative', 'criteria'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $ratings,
        ]);
    }

    /**
     * Simpan rating (bulk)
     */
    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*.alternative_id' => 'required|exists:alternatives,id',
            'ratings.*.criteria_id' => 'required|exists:criteria,id',
            'ratings.*.rating' => 'required|integer|min:1|max:5',
            'ratings.*.user_id' => 'nullable|exists:users,id',
        ]);

        $dmId = $request->input('user_id');

        // Hapus data lama untuk DM ini (jika ada)
        if ($dmId) {
            AlternativeRating::where('user_id', $dmId)->delete();
        } else {
            AlternativeRating::whereNull('user_id')->delete();
        }

        // Simpan data baru
        foreach ($validated['ratings'] as $rating) {
            if (!isset($rating['user_id']) && $dmId) {
                $rating['user_id'] = $dmId;
            }
            AlternativeRating::create($rating);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ratings saved successfully',
        ], 201);
    }

    /**
     * Simpan rating dari matriks
     */
    public function storeMatrix(Request $request)
    {
        $validated = $request->validate([
            'matrix' => 'required|array',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $alternatives = Alternative::orderBy('id')->get();
        $criteria = Criteria::orderBy('id')->get();
        
        $m = $alternatives->count();
        $n = $criteria->count();
        $matrix = $validated['matrix'];
        $dmId = $validated['user_id'] ?? $request->query('dm_id');

        // Validasi ukuran matriks
        if (count($matrix) !== $m) {
            return response()->json([
                'success' => false,
                'message' => 'Matrix size must be ' . $m . 'x' . $n,
            ], 400);
        }

        // Hapus data lama untuk DM ini (jika ada)
        if ($dmId) {
            AlternativeRating::where('user_id', $dmId)->delete();
        } else {
            AlternativeRating::whereNull('user_id')->delete();
        }

        // Simpan matriks
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                AlternativeRating::create([
                    'alternative_id' => $alternatives[$i]->id,
                    'criteria_id' => $criteria[$j]->id,
                    'rating' => $matrix[$i][$j],
                    'user_id' => $dmId,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating matrix saved successfully for DM ' . ($dmId ?? 'default'),
        ], 201);
    }
}
