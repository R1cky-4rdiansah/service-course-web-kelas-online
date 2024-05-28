<?php

use App\Http\Controllers\MentorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get("mentors", [MentorController::class, 'index']);
Route::get("mentors/{id}", [MentorController::class, 'show']);
Route::post("mentors", [MentorController::class, 'create']);
Route::put("mentors/{id}", [MentorController::class, 'update']);
Route::delete("mentors/{id}", [MentorController::class, 'destroy']);
