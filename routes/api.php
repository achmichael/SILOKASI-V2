<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\PairwiseComparisonController;
use App\Http\Controllers\AnpInterdependencyController;
use App\Http\Controllers\AlternativeRatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BordaPointController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// activate web middleware for supporting session management
Route::middleware('web')->prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::middleware(['auth:api', 'jwt.verify'])->group(function () {
    Route::get('/user/current', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'user'           => $user,
            ],
        ]);
    });

    Route::post('/profile/update', [ProfileController::class, 'update']);

    Route::prefix('criteria')->group(function () {
        Route::get('/', [CriteriaController::class, 'index']);
        Route::get('/{id}', [CriteriaController::class, 'show']);
        Route::post('/', [CriteriaController::class, 'store']);
        Route::put('/{id}', [CriteriaController::class, 'update']);
        Route::delete('/{id}', [CriteriaController::class, 'destroy']);
    });

    Route::prefix('alternatives')->group(function () {
        Route::get('/', [AlternativeController::class, 'index']);
        Route::get('/{id}', [AlternativeController::class, 'show']);
        Route::post('/', [AlternativeController::class, 'store']);
        Route::put('/{id}', [AlternativeController::class, 'update']);
        Route::delete('/{id}', [AlternativeController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/decision-makers', [UserController::class, 'getDecisionMakers']);
        Route::post('/{id}/set-decision-maker', [UserController::class, 'setDecisionMaker']);
    });

    // Input Data Routes
    Route::prefix('pairwise-comparisons')->group(function () {
        Route::get('/', [PairwiseComparisonController::class, 'index']);
        Route::post('/bulk', [PairwiseComparisonController::class, 'storeBulk']);
        Route::post('/matrix', [PairwiseComparisonController::class, 'storeMatrix']);
    });

    Route::prefix('anp-interdependencies')->group(function () {
        Route::get('/', [AnpInterdependencyController::class, 'index']);
        Route::post('/matrix', [AnpInterdependencyController::class, 'storeMatrix']);
    });

    Route::prefix('alternative-ratings')->group(function () {
        Route::get('/', [AlternativeRatingController::class, 'index']);
        Route::post('/bulk', [AlternativeRatingController::class, 'storeBulk']);
        Route::post('/matrix', [AlternativeRatingController::class, 'storeMatrix']);
    });

    Route::prefix('borda-points')->group(function () {
        Route::get('/', [BordaPointController::class, 'index']);
        Route::post('/bulk', [BordaPointController::class, 'storeBulk']);
        Route::post('/matrix', [BordaPointController::class, 'storeMatrix']);
    });

    // Calculation Routes
    Route::prefix('calculate')->group(function () {
        Route::post('/ahp', [CalculationController::class, 'calculateAHP']); // calculate priority weights
        Route::post('/anp', [CalculationController::class, 'calculateANP']);
        Route::post('/wp', [CalculationController::class, 'calculateWP']);
        Route::post('/borda', [CalculationController::class, 'calculateBorda']);
        Route::post('/all', [CalculationController::class, 'calculateAll']);
    });

    // Results Routes
    Route::prefix('results')->group(function () {
        Route::get('/', [CalculationController::class, 'getResults']);
        Route::get('/final-ranking', [CalculationController::class, 'getFinalRanking']);
        Route::get('/borda', [CalculationController::class, 'getBordaResults']);
    });
});
