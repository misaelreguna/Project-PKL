<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::controller(LoginController::class)->group(function(){
    Route::get('login','login')->name('login');
    Route::post('login','loginAction')->name('login.action' );
    Route::get('user',function () {
     return view('user/index');
    })->name('user.index');

    Route::get('logout','logout')->middleware('auth')->name('logout');
});

// Route::controller(AdminController::class)->group(function(){
//     Route::get('admin','index')->name('admin.index');
// });

// Route::controller(BankController::class)->group(function(){
//     Route::get('bank','index')->name('bank.index');
// });

Route::middleware(['auth'])->group(function(){
    Route::resource('admin',AdminController::class);
    Route::resource('bank',BankController::class);
});