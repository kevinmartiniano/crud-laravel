<?php

use App\Http\Controllers\Contacts\FindContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts/{id}', FindContactController::class);
