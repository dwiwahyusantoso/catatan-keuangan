<?php

namespace App\Helpers;

use App\Models\Transaction as ModelsTransaction;

Class Transaction
{
    public static function checker( $id, $selisih )
    {
        $transaction = ModelsTransaction::where('id', $id)->first('saldo');
        $transaction->saldo -= $selisih;
        $saldo = $transaction->saldo;

        return $saldo;
    }

    public static function saver($id, $selisih)
    {
        $transaction = ModelsTransaction::where('id', $id)->first();
        $transaction->saldo -= $selisih;
        $transaction->save();
    }
}
