<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $photoUrl = '';

        try {
            $response = Http::get('https://foodish-api.herokuapp.com/api/');
            $photoUrl = $response->json()['image'];
        }
        catch (ConnectionException $error) {
            $photoUrl = 'https://via.placeholder.com/200x400.png/0099ff?text=food';
        }

        return [
            'name' => fake()->userName(),
            'photo' => $photoUrl,
            'description' => fake()->text(),
            'price' => fake()->biasedNumberBetween(3000, 80000),
        ];
    }
}
