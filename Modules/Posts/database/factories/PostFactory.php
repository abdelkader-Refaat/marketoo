<?php

namespace Modules\Posts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Posts\Enums\PostPrivacyEnum;
use Modules\Users\App\Models\User;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Posts\App\Models\Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ??User::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'privacy' => $this->faker->randomElement(array_column(PostPrivacyEnum::cases(), 'value')),
            'is_promoted' => $this->faker->boolean,
            'event_name' => $this->faker->optional()->words(3, true),
            'event_date_time' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'event_description' => $this->faker->optional()->paragraph,
            'repost_id' => null,
            'repost_text' => $this->faker->optional()->text,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

