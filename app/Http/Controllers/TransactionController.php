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
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        $saldo = Transaction::orderBy('created_at', 'desc')->first('saldo');
        $savings = Saving::orderBy('created_at', 'desc')->get();

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
        // $request->validate([
        //     'username' => 'required',
        //     'jenis_transaksi' => 'required',
        //     'kategori' => 'required',
        //     'description' => 'required',
        //     'nominal' => 'required',
        //     'saldo' => 'required'
        // ]);

        $saldo = Transaction::orderBy('created_at', 'desc')->first('saldo');
        $transaction = new Transaction;
        $transaction->username = 'admin';
        $transaction->jenis_transaksi = $request->jenis_transaksi;
        $transaction->kategori = $request->kategori;
        $transaction->description = $request->description;
        $transaction->nominal = $request->nominal;
        if (!isset($saldo)){
            $transaction->saldo = $request->nominal;
        } else {
            $transaction->saldo = $saldo->saldo - $request->nominal;
            if ( $request->jenis_transaksi == "masuk") {
                $transaction->saldo = $saldo->saldo + $request->nominal;
            }
        }
        $transaction->date = date("Y-m-d H:i:s");
        $transaction->save();

        return redirect('/dashboard')
            ->with('success', 'Transaction created successfully.');
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
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
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
        // $request->validate([
        //     'username' => 'required',
        //     'jenis_transaksi' => 'required',
        //     'kategori' => 'required',
        //     'description' => 'required',
        //     'nominal' => 'required',
        //     'saldo' => 'required'
        // ]);
        $transaction = Transaction::where('id', $id)->first();
        if ($transaction->saving_id) {
            SavingController::update($id, $request->nominal, $request->jenis_transaksi, $transaction->kategori, $transaction->saving_id);
        } else {
            $saldo_mula = $transaction->saldo;
            $transaction->username = 'admin';
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
            $transaction->save();

            $transactions = Transaction::where('created_at', '>', $transaction->created_at)->get('id');
            $selisih = $saldo_mula - $transaction->saldo;
            foreach ($transactions as $row) {
                $this->saver($row->id, $selisih);
            }
        }

        return redirect('/dashboard')->with('success', 'Transaction updated successfully.');
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
            SavingController::destroy($transaction->nominal, $transaction->jenis_transaksi, $transaction->kategori, $transaction->saving_id);
        }
        $transactions = Transaction::where('created_at', '>', $transaction->created_at)->get(['id', 'nominal', 'jenis_transaksi']);
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
