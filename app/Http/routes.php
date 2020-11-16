<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(array('before' => 'csrf', ['middleware' => 'web']), function () {
    Route::get('course-list', 'CourseController@courseList');
    Route::get('program-list', 'CourseController@programList');
    Route::get('semester-list', 'CourseController@semesterList');
    Route::get('batch-list', 'CourseController@batchList');
    Route::get('schedule-list', 'CourseController@ScheduleList');
    Route::post('login', 'UserController@login');
});
