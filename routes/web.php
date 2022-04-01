<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingController;

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

Route::get('/', function (){
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login',[LoginController::class, 'store'])->name('login');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/register',[RegistrationController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register',[RegistrationController::class, 'store'])->name('register');

Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/transaction', [TransactionController::class, 'store']);
Route::post('/tabungan', [SavingController::class, 'store']);
Route::get('/show/{id}/{saving_name}', [SavingController::class, 'show'])->name('show');
Route::get('/edit/{id}', [TransactionController::class, 'edit'])->name('edit');
Route::post('/update/{id}', [TransactionController::class, 'update'])->name('update');
Route::get('/delete/{id}', [TransactionController::class, 'destroy'])->name('delete');

