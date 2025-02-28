<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BookmarkStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookmark>
 */
final class BookmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'favicon' => 'asd',
            'status' => BookmarkStatus::PUBLISHED->value,
            'user_id' => 1,
        ];
    }
}
