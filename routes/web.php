<?php

use App\Http\Controllers\Contacts\CreateContactController;
use App\Http\Controllers\Contacts\FindContactController;
use App\Http\Controllers\Contacts\ListContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts', ListContactController::class);
Route::get('/contacts/{id}', FindContactController::class);

Route::post('/contacts', CreateContactController::class);
