<?php

namespace Database\Factories;

use App\Models\Communicationmessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommunicationmessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Communicationmessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender' => rand(1, 25),
            'receiver' => rand(25, 50),
            'communicationmessage' => $this->faker->paragraph,
            'is_viewed' => rand(0, 1)
        ];
    }
}
