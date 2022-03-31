<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Http\Requests\StoreSavingRequest;
use App\Http\Requests\UpdateSavingRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSavingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSavingRequest $request)
    {
        //
        try {
            DB::beginTransaction();
                $saving = Saving::where('saving_name', $request->nama_tabungan)->orderBy('created_at', 'desc')->first();
                if (!$saving) {
                    $saving = new Saving;
                    if ($request->jenis_transaksi == 'keluar') {
                        return redirect('/dashboard')->with('danger', 'Saving failed');
                    }
                    $saving->username = "admin";
                    $saving->saldo = $request->nominal;
                    $saving->saving_name = $request->nama_tabungan;
                    $saving->save();
                } else {
                    switch ($request->jenis_transaksi) {
                        case 'masuk':
                            $saving->saldo += $request->nominal;
                            $saving->save();
                            break;

                        default:
                            $saving->saldo -= $request->nominal;
                            $saving->save();
                            break;
                    }
                }

                $saldo = Transaction::orderBy('created_at', 'desc')->first();
                $transaction = new Transaction;
                $transaction->saving_id = $saving->id;
                $transaction->username = 'admin';
                $transaction->kategori = "Tabungan $request->nama_tabungan";
                $jenis_transaksi = "masuk";
                if ( $request->jenis_transaksi == "masuk") {
                    $jenis_transaksi = "keluar";
                }
                $transaction->jenis_transaksi = $jenis_transaksi;
                $transaction->nominal = $request->nominal;
                if ( $jenis_transaksi == "keluar") {
                    $transaction->saldo = $saldo->saldo - $request->nominal;
                    $transaction->description = "$request->jenis_transaksi uang ke tabungan $request->nama_tabungan";
                } else {
                    $transaction->saldo = $saldo->saldo + $request->nominal;
                    $transaction->description = "$request->jenis_transaksi uang dari tabungan $request->nama_tabungan";
                }
                $transaction->date = date("Y-m-d H:i:s");
                if ($transaction->saldo < 0) {
                    return redirect('/dashboard')->with('danger', 'Saving failed');
                }
                $transaction->save();

            DB::commit();
        } catch (\Exception$e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        return redirect('/dashboard')->with('success', 'Saving successfully');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Saving  $saving
     * @return \Illuminate\Http\Response
     */
    public function show($id, $saving_name)
    {
        //
        $transactions = Transaction::where('saving_id', $id)->orderBy('created_at', 'desc')->get();

        return view('saving.index', compact('transactions', 'saving_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Saving  $saving
     * @return \Illuminate\Http\Response
     */
    public function edit(Saving $saving)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSavingRequest  $request
     * @param  \App\Models\Saving  $saving
     * @return \Illuminate\Http\Response
     */
    public static function update($id, $request_nominal, $request_jenis, $kategori, $saving_id)
    {
        //
        try {
            $saving = Saving::where('id', $saving_id)->first();
            $transaction = Transaction::where('id', $id)->first();
            $saldo_mula = $transaction->saldo;
            $jenis_mula = $transaction->jenis_transaksi;
            $nominal_mula = $transaction->nominal;
            switch ($transaction->jenis_transaksi) {
                case 'masuk':
                    $saldo_sebelumnya = $transaction->saldo - $transaction->nominal;
                    break;
    
                default:
                    $saldo_sebelumnya = $transaction->saldo + $transaction->nominal;
                    break;
            }
            switch ($request_jenis) {
                case 'masuk':
                    $transaction->saldo = $saldo_sebelumnya - $request_nominal;
                    $transaction->description = "keluar uang ke tabungan $saving->saving_name";
                    break;
    
                default:
                    $transaction->saldo = $saldo_sebelumnya + $request_nominal;
                    $transaction->description = "masuk uang dari tabungan $saving->saving_name";
                    break;
            }
            $jenis_transaksi = "masuk";
            if ( $request_jenis == "masuk") {
                $jenis_transaksi = "keluar";
            }
            $transaction->jenis_transaksi = $jenis_transaksi;
            $transaction->nominal = $request_nominal;
            $transaction->date = $transaction->date;
            $transaction->save();
    
            $transactions = Transaction::where('created_at', '>', $transaction->created_at)->get('id');
            $selisih = $saldo_mula - $transaction->saldo;
            foreach ($transactions as $row) {
                TransactionController::saver($row->id, $selisih);
            }
    
            switch ($jenis_mula) {
                case 'masuk':
                    $saldo_saving_sebelumnya = $saving->saldo + $nominal_mula;
                    break;
    
                default:
                    $saldo_saving_sebelumnya = $saving->saldo - $nominal_mula;
                    break;
            }
            if ( $request_jenis == 'masuk') {
                $saving->saldo = $saldo_saving_sebelumnya + $request_nominal;
                $saving->save();
            } else {
                $saving->saldo = $saldo_saving_sebelumnya - $request_nominal;
                $saving->save();
            }

            DB::commit();
        } catch (\Exception$e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Saving  $saving
     * @return \Illuminate\Http\Response
     */
    public static function destroy($nominal, $jenis_transaksi, $kategori, $saving_id)
    {
        //
        $saving = Saving::where('id', $saving_id)->first();
        if ( $jenis_transaksi == 'masuk') {
            $saving->saldo += $nominal;
            $saving->save();
        } else {
            $saving->saldo -= $nominal;
            $saving->save();
        }
        if ($saving->saldo == 0){
            $saving->delete();
        }
    }
}