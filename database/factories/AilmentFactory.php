<?php

namespace Database\Factories;

use App\Models\Ailment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AilmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ailment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'ailment_type' => $this->faker->unique()->color(),
            'ailment_stage' => $this->faker->numberBetween(1,4),
        ];
    }
}
