<?php

namespace App\Http\Controllers;

use App\Helpers\Transaction as HelpersTransaction;
use App\Models\Saving;
use App\Http\Requests\StoreSavingRequest;
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
        $request->validate([
            'jenis_transaksi' => 'required',
            'nama_tabungan' => 'required',
            'nominal' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();
                $saving = Saving::where('username', session('username'))->where('saving_name', $request->nama_tabungan)->orderBy('created_at', 'desc')->first();
                if (!$saving) {
                    $saving = new Saving;
                    if ($request->jenis_transaksi == 'keluar') {
                        return redirect('/dashboard')->with('error', 'Saving failed, Don\'t have savings yet');
                    }
                    $saving->username = session('username');
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
                    if ($saving->saldo < 0) {
                        return redirect('/dashboard')->with('error', 'Saving failed, Not enough saving saldo');
                    }
                }

                $saldo = Transaction::where('username', session('username'))->orderBy('created_at', 'desc')->first();
                $transaction = new Transaction;
                $transaction->saving_id = $saving->id;
                $transaction->username = session('username');
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
                    return redirect('/dashboard')->with('error', 'Saving failed, Not enough primary saldo');
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

        return redirect('/dashboard')->with('success', 'Saving created successfully');;
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
        $transactions = Transaction::where('username', session('username'))->where('saving_id', $id)->orderBy('created_at', 'desc')->get();

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
    public static function update($id, $request_nominal, $request_jenis, $saving_id)
    {
        //
        try {
            $saving = Saving::where('username', session('username'))->where('id', $saving_id)->first();
            $transaction = Transaction::where('username', session('username'))->where('id', $id)->first();
            $saldo_mula = $transaction->saldo;
            $jenis_mula = $transaction->jenis_transaksi;
            $nominal_mula = $transaction->nominal;

            switch ($jenis_mula) {
                case 'masuk':
                    $saldo_saving_sebelumnya = $saving->saldo + $nominal_mula;
                    break;

                default:
                    $saldo_saving_sebelumnya = $saving->saldo - $nominal_mula;
                    break;
            }
            switch ( $request_jenis ) {
                case 'masuk':
                    $saving->saldo = $saldo_saving_sebelumnya + $request_nominal;
                    break;

                default:
                    $saving->saldo = $saldo_saving_sebelumnya - $request_nominal;
                    break;
            }

            if ($saving->saldo < 0) {
                return redirect('/dashboard')->with('error', 'Saving update failed, Not enough saving saldo');
            }

            switch ($transaction->jenis_transaksi) {
                case 'masuk':
                    $saldo_sebelumnya = $saldo_mula - $transaction->nominal;
                    break;

                default:
                    $saldo_sebelumnya = $saldo_mula + $transaction->nominal;
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

            if ( $transaction->saldo < 0 ) {
                return redirect('/dashboard')->with('error', 'Saving update failed, Not enough primary saldo');
            }

            $selisih = $saldo_mula - $transaction->saldo;
            $transactions = Transaction::where('username', session('username'))->where('created_at', '>', $transaction->created_at)->get('id');
            foreach ($transactions as $row) {
                $check_saldo = HelpersTransaction::checker( $row->id, $selisih );
                if ($check_saldo < 0) {
                    return redirect('/dashboard')->with('error', 'Update failed, There are transaction have negatif saldo');
                }
            }
            foreach ($transactions as $row) {
                HelpersTransaction::saver( $row->id, $selisih );
            }

            $transaction->save();
            $saving->save();

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
    public static function destroy($nominal, $jenis_transaksi, $saving_id, $transaction_id)
    {
        //
        $saving = Saving::where('username', session('username'))->where('id', $saving_id)->first();
        $saldo_mula = $saving->saldo;
        $transaction = Transaction::where('username', session('username'))->where('id', $transaction_id)->first();

        switch ( $jenis_transaksi ) {
            case 'masuk':
                $saving->saldo += $nominal;
                break;

            default:
                $saving->saldo -= $nominal;
                break;
        }
        $selisih = $saldo_mula - $saving->saldo;

        if ( $saving->saldo < 0 ) {
            return redirect('/dashboard')->with('error', 'Can\'t delete this saving. Saldo must positif');
        } else {
            $transactions = Transaction::where('username', session('username'))->where('created_at', '>', $transaction->created_at)->get('id');
            foreach ($transactions as $row) {
                $check_saldo = HelpersTransaction::checker( $row->id, $selisih );
                if ($check_saldo < 0) {
                    return redirect('/dashboard')->with('error', 'Delete failed, There are transaction have negatif saldo');
                }
            }
            foreach ($transactions as $row) {
                HelpersTransaction::saver( $row->id, $selisih );
            }
            $saving->save();
            $transaction->delete();
        }
        if ($saving->saldo == 0){
            $saving->delete();
        }

        return redirect('/dashboard')->with('danger', 'Saving deleted successfully');
    }
}
