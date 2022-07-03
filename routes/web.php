<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/plants', 'PlantController@index')->name('plants');
Route::get('/plants/setting', 'PlantController@setting')->name('plant_setting');
Route::post('/plants/setting', 'PlantController@add')->name('plant_add');
Route::post('/plants/save_plant', 'PlantController@save_plant')->name('save_plant');

Route::get('/tasks', 'TaskController@index')->name('tasks');
Route::post('/tasks/complete', 'TaskController@complete')->name('complete_task');
Route::post('/tasks/complete_multitasks', 'TaskController@complete_multitasks')->name('complete_multitasks');


Route::get('/welcome', 'PlantController@welcome')->name('welcome');
// Route::get('/', 'UsersController@index')->name('users');
Route::get('/transactions', 'TransactionController@index')->name('transactions');
Route::get('/punch_chains', 'TransactionController@punch_chains')->name('punch_chains');
Route::get('/settings', 'SettingController@index')->name('settings');
Route::post('/settings', 'SettingController@update')->name('update_settings');
