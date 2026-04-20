<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/verify-2fa', 'auth.verify-2fa')->name('verify-2fa');
Route::view('/notes', 'notes.index')->name('notes');
