<?php

use App\Http\Controllers\MentorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LessonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Route Mentor
Route::get("mentors", [MentorController::class, 'index']);
Route::get("mentors/{id}", [MentorController::class, 'show']);
Route::post("mentors", [MentorController::class, 'create']);
Route::put("mentors/{id}", [MentorController::class, 'update']);
Route::delete("mentors/{id}", [MentorController::class, 'destroy']);

//Route Course
Route::get("courses", [CourseController::class, 'index']);
Route::get("courses/{id}", [CourseController::class, 'show']);
Route::post("courses", [CourseController::class, 'create']);
Route::put("courses/{id}", [CourseController::class, 'update']);
Route::delete("courses/{id}", [CourseController::class, 'destroy']);

//Route Chapter
Route::get("chapters", [ChapterController::class, 'index']);
Route::get("chapters/{id}", [ChapterController::class, 'show']);
Route::post("chapters", [ChapterController::class, 'create']);
Route::put("chapters/{id}", [ChapterController::class, 'update']);
Route::delete("chapters/{id}", [ChapterController::class, 'destroy']);

//Route Lesson
Route::get("lessons", [LessonController::class, 'index']);
Route::get("lessons/{id}", [LessonController::class, 'show']);
Route::post("lessons", [LessonController::class, 'create']);
Route::put("lessons/{id}", [LessonController::class, 'update']);
Route::delete("lessons/{id}", [LessonController::class, 'destroy']);