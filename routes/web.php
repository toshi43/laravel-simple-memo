<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FolderController;

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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('{/home', [HomeController::class, 'index'])->name('home');
Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('edit');
Route::post('/update', [HomeController::class, 'update'])->name('update');
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroy');
Route::get('/folders/create', [FolderController::class, 'showCreateForm'])->name('folders/formcreate');
Route::post('/folders/create', [FolderController::class, 'create']);
Route::get('/folders/{folder_id}/edit', [HomeController::class, 'folderedit'])->name('folderedit');
Route::post('/folders/{folder_id}/update', [FolderController::class, 'update'])->name('folderupdate');
Route::post('/folders/{folder_id}/destroy',[FolderController::class, 'destroy'])->name('folderdestroy');

//Route::get('/folders/{id}', [HomeController::class, 'folders'])->name('folders.index');


//destory