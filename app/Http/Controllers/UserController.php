<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get all decision makers
     */
    public function getDecisionMakers()
    {
        $decisionMakers = User::decisionMakers()->orderBy('id')->get();
        
        return response()->json([
            'success' => true,
            'data' => $decisionMakers,
        ]);
    }

    /**
     * Set user as decision maker
     */
    public function setDecisionMaker(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:admin,decision_maker',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'data' => $user,
        ]);
    }
}
