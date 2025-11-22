<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use Illuminate\Http\Request;

class AlternativeController extends Controller
{
    /**
     * Tampilkan semua alternatif
     */
    public function index()
    {
        $alternatives = Alternative::orderBy('id')->get();
        
        return response()->json([
            'success' => true,
            'data' => $alternatives,
        ]);
    }

    /**
     * Simpan alternatif baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:alternatives',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $alternative = Alternative::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alternative created successfully',
            'data' => $alternative,
        ], 201);
    }

    /**
     * Update alternatif
     */
    public function update(Request $request, $id)
    {
        $alternative = Alternative::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|string|max:10|unique:alternatives,code,' . $id,
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $alternative->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Alternative updated successfully',
            'data' => $alternative,
        ]);
    }

    /**
     * Hapus alternatif
     */
    public function destroy($id)
    {
        $alternative = Alternative::findOrFail($id);
        $alternative->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alternative deleted successfully',
        ]);
    }
}
