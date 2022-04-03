<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Saving;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $transactions = Transaction::where('username', session('username'))->orderBy('created_at', 'desc')->get();
        $saldo = Transaction::where('username', session('username'))->orderBy('created_at', 'desc')->first('saldo');
        $savings = Saving::where('username', session('username'))->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('transactions', 'saldo', 'savings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        //
        $request->validate([
            'jenis_transaksi' => 'required',
            'kategori' => 'required',
            'nominal' => 'required|numeric'
        ]);

        $saldo = Transaction::where('username', session('username'))->orderBy('created_at', 'desc')->first('saldo');
        $transaction = new Transaction;
        $transaction->username = session('username');
        $transaction->jenis_transaksi = $request->jenis_transaksi;
        $transaction->kategori = $request->kategori;
        $transaction->description = $request->description;
        $transaction->nominal = $request->nominal;
        if (!isset($saldo)){
            $transaction->saldo = $request->nominal;
        } else {
            switch ($request->jenis_transaksi) {
                case 'masuk':
                    $transaction->saldo = $saldo->saldo + $request->nominal;
                    break;

                default:
                    $transaction->saldo = $saldo->saldo - $request->nominal;
                    break;
            }
        }

        if ($transaction->saldo < 0 or (!isset($saldo) and $request->jenis_transaksi == 'keluar')) {
            return redirect('/dashboard')->with('error', 'Transaction failed, Not enough primary saldo');
        }
        $transaction->date = date("Y-m-d H:i:s");
        $transaction->save();

        return redirect('/dashboard')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $transactions = Transaction::where('username', session('username'))->orderBy('created_at', 'desc')->get();
        $record = Transaction::find($id);
        return view('transaction.edit-transaction', compact('record', 'transactions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction, $id)
    {
        //
        $request->validate([
            'jenis_transaksi' => 'required',
            'nominal' => 'required|numeric'
        ]);

        $transaction = Transaction::where('username', session('username'))->where('id', $id)->first();
        if ($transaction->saving_id) {
            SavingController::update($id, $request->nominal, $request->jenis_transaksi, $transaction->saving_id);
        } else {
            $saldo_mula = $transaction->saldo;
            $transaction->kategori = $request->kategori;
            $transaction->description = $request->description;
            switch ($transaction->jenis_transaksi) {
                case 'masuk':
                    $saldo_sebelumnya = $transaction->saldo - $transaction->nominal;
                    break;
                default:
                    $saldo_sebelumnya = $transaction->saldo + $transaction->nominal;
                    break;
            }
            switch ($request->jenis_transaksi) {
                case 'masuk':
                    $transaction->saldo = $saldo_sebelumnya + $request->nominal;
                    break;
                default:
                    $transaction->saldo = $saldo_sebelumnya - $request->nominal;
                    break;
            }
            $transaction->jenis_transaksi = $request->jenis_transaksi;
            $transaction->nominal = $request->nominal;
            $transaction->date = $transaction->date;
            if ($transaction->saldo < 0) {
                return redirect('/dashboard')->with('error', 'Update failed, Not enough primary saldo');
            }
            $transaction->save();

            $transactions = Transaction::where('username', session('username'))->where('created_at', '>', $transaction->created_at)->get('id');
            $selisih = $saldo_mula - $transaction->saldo;
            foreach ($transactions as $row) {
                $this->saver($row->id, $selisih);
            }

            return redirect('/dashboard')->with('success', 'Transaction updated successfully.');
        }

        return redirect('/dashboard');
    }

    public static function saver($id, $selisih)
    {
        $transaction = Transaction::where('id', $id)->first();
        $transaction->saldo -= $selisih;
        $transaction->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $transaction = Transaction::find($id);
        if ($transaction->saving_id) {
            SavingController::destroy($transaction->nominal, $transaction->jenis_transaksi, $transaction->saving_id);
        }
        $transactions = Transaction::where('username', session('username'))->where('created_at', '>', $transaction->created_at)->get(['id', 'nominal', 'jenis_transaksi']);
        foreach ($transactions as $row) {
            if ($transaction->jenis_transaksi == 'masuk') {
                $selisih = $transaction->nominal;
            } else {
                $selisih = -$transaction->nominal;
            }

            $this->saver($row->id, $selisih);
        }
        $transaction->delete();
        return redirect('/dashboard')->with('danger', 'Transaction deleted successfully.');
    }
}
