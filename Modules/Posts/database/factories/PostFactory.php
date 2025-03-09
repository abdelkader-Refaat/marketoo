<?php

namespace Modules\Posts\Database\Factories;

use App\Models\User;
use Modules\Posts\Models\Post;
use Modules\Posts\Enums\PostPrivacyEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'privacy' => $this->faker->randomElement(array_column(PostPrivacyEnum::cases(), 'value')),
            'is_promoted' => $this->faker->boolean,

            // Event fields
            'event_name' => $this->faker->optional()->words(3, true),
            'event_date_time' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'event_description' => $this->faker->optional()->paragraph,

            // Repost fields
            'repost_id' => null, // This can be handled later for nested reposts
            'repost_text' => $this->faker->optional()->text,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

