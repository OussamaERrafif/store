<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word, // Generate a random product name.
            'description' => $this->faker->sentence, // Generate a random sentence for the description.
            'price' => $this->faker->randomFloat(2, 1, 1000), // Generate a random price between 1 and 1000.
            'category_id' => Category::factory(), // Generate a new category using the CategoryFactory.
            'image' => $this->faker->imageUrl(640, 480, 'products', true, 'Faker'), // Generate a random image URL.
        ];
    }
}
