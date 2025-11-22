<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/user/current', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'decision_maker' => $user?->decisionMaker,
            ],
        ]);
    });
});

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Criteria Routes
Route::prefix('criteria')->name('criteria.')->group(function () {
    Route::get('/', function () {
        return view('criteria.index');
    })->name('index');
});

// Alternatives Routes
Route::prefix('alternatives')->name('alternatives.')->group(function () {
    Route::get('/', function () {
        return view('alternatives.index');
    })->name('index');
});

// Decision Makers Routes
Route::prefix('decision-makers')->name('decision-makers.')->group(function () {
    Route::get('/', function () {
        return view('decision-makers.index');
    })->name('index');
});

// Pairwise Comparison Routes
Route::prefix('pairwise')->name('pairwise.')->group(function () {
    Route::get('/', function () {
        return view('pairwise.index');
    })->name('index');
});

// ANP Interdependency Routes
Route::prefix('anp')->name('anp.')->group(function () {
    Route::get('/', function () {
        return view('anp.index');
    })->name('index');
});

// Alternative Ratings Routes
Route::prefix('ratings')->name('ratings.')->group(function () {
    Route::get('/', function () {
        return view('ratings.index');
    })->name('index');
});

// Results Routes
Route::prefix('results')->name('results.')->group(function () {
    Route::get('/', function () {
        return view('results.index');
    })->name('index');
});
