<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/view-form/{id}', 'HomeController@viewForm')->name('viewForm')->where('id', '([0-9]+)');;

//Route::middleware(['auth'])->group(function () {
Route::middleware(['role'])->group(function () {
	Route::get('/list-form', 'FormController@listForm')->name('listForm');
	Route::get('/add-form', 'FormController@addForm')->name('addForm');
	Route::get('/edit-form/{id}', 'FormController@editForm')->name('editForm')->where('id', '([0-9]+)');
	Route::post('/save-form', 'FormController@saveForm')->name('saveForm');
	Route::post('/delete-form', 'FormController@deleteForm')->name('deleteForm');
	Route::get('/form-elements/{id}', 'FormController@formElements')->name('formElements')->where('id', '([0-9]+)');
	Route::post('/save-form-elements', 'FormController@saveFormElements')->name('saveFormElements');
	Route::post('/edit-form-elements', 'FormController@editFormElements')->name('editFormElements');
	Route::delete('/delete-form-elements/{id}', 'FormController@deleteFormElements')->name('deleteFormElements')->where('id', '([0-9]+)');
});
