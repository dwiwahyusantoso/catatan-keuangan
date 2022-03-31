<?php

use App\Models\Transaction;
use App\Models\Saving;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\StoreSavingRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TransactionController::class, 'index']);

Route::post('/transaction', [TransactionController::class, 'store']);

Route::post('/tabungan', [SavingController::class, 'store']);

Route::get('/show/{id}/{saving_name}', [SavingController::class, 'show'])->name('show');

Route::get('/edit/{id}', [TransactionController::class, 'edit'])->name('edit');

Route::post('/update/{id}', [TransactionController::class, 'update'])->name('update');

Route::get('/delete/{id}', [TransactionController::class, 'destroy'])->name('delete');
