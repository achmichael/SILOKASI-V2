<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware('')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('criteria')->name('criteria.')->group(function () {
        Route::get('/', function () {
            return view('criteria.index');
        })->name('index');
    });

    Route::prefix('alternatives')->name('alternatives.')->group(function () {
        Route::get('/', function () {
            return view('alternatives.index');
        })->name('index');
    });

    Route::prefix('decision-makers')->name('decision-makers.')->group(function () {
        Route::get('/', function () {
            return view('decision-makers.index');
        })->name('index');
    });

    Route::prefix('pairwise')->name('pairwise.')->group(function () {
        Route::get('/', function () {
            return view('pairwise.index');
        })->name('index');
    });

    Route::prefix('anp')->name('anp.')->group(function () {
        Route::get('/', function () {
            return view('anp.index');
        })->name('index');
    });

    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', function () {
            return view('ratings.index');
        })->name('index');
    });

    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', function () {
            return view('results.index');
        })->name('index');
    });
});
