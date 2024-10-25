<?php

namespace Database\Factories;

use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class JwtTokenFactory extends Factory
{
    protected $model = JwtToken::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'subject' => $this->faker->word(),
            'audience' => $this->faker->word(),
            'expiration_time' => Carbon::now(),
            'iat' => Carbon::now(),
            'custom_claims' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }

}
