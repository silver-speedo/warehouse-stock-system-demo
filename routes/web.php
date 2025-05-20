<?php

use Illuminate\Support\Facades\Route;

Route::get('/horizon', function () {
    return redirect('/horizon/dashboard');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
