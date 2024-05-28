<?php

use App\Http\Controllers\MentorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post("mentors", [MentorController::class, 'create']);
