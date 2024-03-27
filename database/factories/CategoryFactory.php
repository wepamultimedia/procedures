<?php

namespace Wepa\Procedures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wepa\Procedures\Models\Category;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected static int $position = 1;

    protected $model = Category::class;

    public function configure(): CategoryFactory
    {
        self::$position = Category::nextPosition();

        return $this->afterMaking(function (Category $category) {
            $category->position = self::$position++;
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'image' => $this->faker->imageUrl,
        ];
    }
}
