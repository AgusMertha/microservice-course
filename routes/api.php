<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\MyCourseController;

Route::get('mentors', [MentorController::class, 'index']);
Route::post('mentors', [MentorController::class, 'create']);
Route::get('mentors/{id}', [MentorController::class, 'show']);
Route::put('mentors/{id}', [MentorController::class, 'update']);
Route::delete('mentors/{id}', [MentorController::class, 'destroy']);

Route::get('courses', [CourseController::class, 'index']);
Route::post('courses', [CourseController::class, 'create']);
Route::get('courses/{id}', [CourseController::class, 'show']);
Route::put('courses/{id}', [CourseController::class, 'update']);
Route::delete('courses/{id}', [CourseController::class, 'destroy']);

Route::get('chapters', [ChapterController::class, 'index']);
Route::post('chapters', [ChapterController::class, 'create']);
Route::get('chapters/{id}', [ChapterController::class, 'show']);
Route::put('chapters/{id}', [ChapterController::class, 'update']);
Route::delete('courses/{id}', [ChapterController::class, 'destroy']);

Route::get('lessons', [LessonController::class, 'index']);
Route::post('lessons', [LessonController::class, 'create']);
Route::get('lessons/{id}', [LessonController::class, 'show']);
Route::put('lessons/{id}', [LessonController::class, 'update']);
Route::delete('lessons/{id}', [LessonController::class, 'destroy']);

Route::post('image-course', [ImageCourseController::class, 'create']);
Route::delete('image-course/{id}', [ImageCourseController::class, 'destroy']);

Route::post('my-courses', [MyCourseController::class, 'create']);