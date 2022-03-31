<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Saving>
 */
class SavingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tabungan = [
            'rumah',
            'mobil',
        ];
        return [
            //
            'username' => $this->faker->name(),
            'saving_name' => Arr::random($tabungan),
            'saldo' => $this->faker->randomNumber(7, true),
        ];
    }
}
