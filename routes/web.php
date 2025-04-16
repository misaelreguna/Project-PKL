<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
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
    Route::group(['middleware' => ['checkRole:admin']],function(){
        Route::resource('admin',controller:AdminController::class)->except('show');
        Route::post('admin/confirmtopup/{id}',[AdminController::class, 'confirmtopup'])->name('admin.confirmtopup');
    });

    Route::group(['middleware' => ['checkRole:user']],function(){
        Route::resource('user',controller:UserController::class)->except('show');
        Route::post('user/topup',[UserController::class, 'topup'])->name('user.topup');
        Route::post('user/tariktunai', [UserController::class, 'tariktunai'])->name('user.tariktunai');
        Route::post('user/transfer', [UserController::class, 'transfer'])->name('user.transfer');
    });

    Route::group(['middleware' => ['checkRole:bank']],function(){
        Route::resource('bank',controller:BankController::class)->except('show');
        Route::post('bank/topup',[BankController::class,'topup'])->name('bank.topup');
        Route::post('bank/tariktunai',[BankController::class,'tariktunai'])->name('bank.tariktunai');
    });

    // Route::resource('admin',AdminController::class);
    // Route::resource('bank',BankController::class);
    // Route::resource('user',UserController::class);

});