<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\DecisionMaker;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update User
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update or Create Decision Maker
        if ($user->role === 'decision_maker') {
            $decisionMaker = DecisionMaker::where('user_id', $user->id)->first();

            if ($decisionMaker) {
                $decisionMaker->name = $request->name; // Sync name
                $decisionMaker->save();
            } else {
                // Create if not exists (though it should ideally exist)
                DecisionMaker::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'weight' => 0, // Default weight if creating new
                ]);
            }
        }

        $user->load('decisionMaker');

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
                'decision_maker' => $user->decisionMaker
            ]
        ]);
    }
}
