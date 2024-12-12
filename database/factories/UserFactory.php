<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    /**
     * State pour un utilisateur avec un token valide.
     */
    public function withValidToken(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'authentication_token' => Str::random(20),
                'authentication_token_generated_at' => Carbon::now(),
            ];
        });
    }

    /**
     * State pour un utilisateur avec un token expiré.
     */
    public function withExpiredToken(?string $token): Factory
    {
        return $this->state(function (array $attributes) use ($token) {
            return [
                'authentication_token' => $token ?? Str::random(20),
                'authentication_token_generated_at' => now()->subHours(25),
            ];
        });
    }
}
