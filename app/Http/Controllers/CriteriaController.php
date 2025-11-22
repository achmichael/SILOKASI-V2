<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CriteriaController extends Controller
{
    /**
     * Tampilkan semua kriteria
     */
    public function index()
    {
        $criteria = Criteria::orderBy('id')->get();
        
        return response()->json([
            'success' => true,
            'data' => $criteria,
        ]);
    }

    /**
     * Simpan kriteria baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:criterias',
            'name' => 'required|string',
            'type' => 'required|in:benefit,cost',
        ]);

        $criteria = Criteria::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Criteria created successfully',
            'data' => $criteria,
        ], 201);
    }

    public function show($id)
    {
        $criteria = Criteria::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $criteria,
        ]);
    }

    /**
     * Update kriteria
     */
    public function update(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);
        Log::info('Updating criteria with ID: ' . $id);
        Log::info('Request data: ', $request->all());
        Log::info('criteria object: ', $criteria->toArray());
        $validated = $request->validate([
            'code' => 'sometimes|string|max:10|unique:criterias,code,' . $id,
            'name' => 'sometimes|string',
            'type' => 'sometimes|in:benefit,cost',
        ]);

        $criteria->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Criteria updated successfully',
            'data' => $criteria,
        ]);
    }

    /**
     * Hapus kriteria
     */
    public function destroy($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Criteria deleted successfully',
        ]);
    }
}
