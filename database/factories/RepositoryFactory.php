<?php

namespace Database\Factories;

use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
            'name' => $this->faker->word,
            'last_commit_message' => $this->faker->sentence,
            'status_id' => 1,
        ];
    }

    public function initialized(): Factory
    {
        return $this->state(function () {
            return [
                'status_id' => Repository::INITIALIZED,
            ];
        });
    }

    public function invalid(): Factory
    {
        return $this->state(function () {
            return [
                'status_id' => Repository::INVALID,
            ];
        });
    }

    public function cloned(): Factory
    {
        return $this->state(function () {
            return [
                'status_id' => Repository::CLONED,
            ];
        });
    }
}
