<?php

namespace Database\Factories;

use App\Models\COE;
use Illuminate\Database\Eloquent\Factories\Factory;

class COEFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = COE::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'serial_number' => $this->faker->numberBetween(1094,8475),
            'coe_name' => $this->faker->company,
            'coe_address' => $this->faker->address,
            'coe_type' => 'Federal',
            'coe_state' => $this->faker->state,
            'coe_lga' => $this->faker->locale,
        ];
    }
}
