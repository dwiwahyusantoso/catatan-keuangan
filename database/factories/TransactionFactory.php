<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $jenis = ['masuk', 'keluar'];
        $kategori = [
            'tabungan',
            'transportasi',
            'asuransi',
            'belanja bulanan',
            'pajak',
            'kesehatan',
            'bonus',
            'cicilan',
            'fashion',
        ];
        return [
            //
            'username' => $this->faker->name(),
            'jenis_transaksi' => Arr::random($jenis),
            'kategori' => Arr::random($kategori),
            'description' => $this->faker->sentences(3, true),
            'nominal' => $this->faker->randomNumber(5, true),
            'saldo' => $this->faker->randomNumber(5, true),
            'date' => $this->faker->date(),
        ];
    }
}
