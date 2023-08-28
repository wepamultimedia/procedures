<?php

namespace Wepa\Procedures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wepa\Procedures\Models\Category;
use Wepa\Procedures\Models\Procedure;

/**
 * @extends Factory<Procedure>
 */
class ProcedureFactory extends Factory
{
    protected static int $position = 1;

    protected $model = Procedure::class;

    public function configure(): ProcedureFactory
    {
        self::$position = Procedure::nextPosition();

        return $this->afterMaking(function (Procedure $procedure) {
            $category = Category::inRandomOrder()->first();
            $procedure->category_id = $category->id;
            $procedure->position = self::$position++;
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
            'body' => $this->faker->paragraph,
        ];
    }
}
