<?php

namespace App\Http\Controllers;

use App\Models\DecisionMaker;
use Illuminate\Http\Request;

class DecisionMakerController extends Controller
{
    /**
     * Tampilkan semua decision maker
     */
    public function index()
    {
        $decisionMakers = DecisionMaker::orderBy('id')->get();
        
        return response()->json([
            'success' => true,
            'data' => $decisionMakers,
        ]);
    }

    /**
     * Simpan decision maker baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'weight' => 'required|numeric|min:0',
        ]);

        $dm = DecisionMaker::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Decision maker created successfully',
            'data' => $dm,
        ], 201);
    }

    /**
     * Update decision maker
     */
    public function update(Request $request, $id)
    {
        $dm = DecisionMaker::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'weight' => 'sometimes|numeric|min:0',
        ]);

        $dm->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Decision maker updated successfully',
            'data' => $dm,
        ]);
    }

    /**
     * Hapus decision maker
     */
    public function destroy($id)
    {
        $dm = DecisionMaker::findOrFail($id);
        $dm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Decision maker deleted successfully',
        ]);
    }
}
