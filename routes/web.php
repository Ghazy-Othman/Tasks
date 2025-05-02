<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Hello, I am Dev Ghazy 👾" ; 
});

Route::get('/docs', function () {
    return view('scribe.index');
});
