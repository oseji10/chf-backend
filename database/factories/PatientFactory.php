<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'ailment_id' => $this->faker->numberBetween(1,10),
            'coe_id' => $this->faker->numberBetween(1,7),
            'user_id' => $this->faker->unique()->numberBetween(1,50),
            'phone_no_alt' => $this->faker->phoneNumber(),
            // 'ailment_id' => $this->faker->numberBetween(1,5),
            'yearly_income' => $this->faker->numberBetween(12099,100345),
            'identification_id' => $this->faker->numberBetween(1,3),
            'identification_number' => $this->faker->unique()->numberBetween(1029345, 9345783),

        ];
    }
}
