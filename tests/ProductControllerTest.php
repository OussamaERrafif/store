<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method of ProductController.
     *
     * @return void
     */
    public function testIndex()
    {
        $products = Product::factory()->count(5)->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200)
            ->assertJson($products->toArray());
    }

    /**
     * Test the store method of ProductController with image upload.
     *
     * @return void
     */
    public function testStoreWithImage()
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'category_id' => $category->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
        ];

        $response = $this->post('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson($data);

        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test the store method of ProductController without image upload.
     *
     * @return void
     */
    public function testStoreWithoutImage()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'category_id' => $category->id,
        ];

        $response = $this->post('/api/products', $data);

        $response->assertStatus(201)
            ->assertJson($data);

        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test the show method of ProductController.
     *
     * @return void
     */
    public function testShow()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson($product->toArray());
    }

    /**
     * Test the update method of ProductController with image update.
     *
     * @return void
     */
    public function testUpdateWithImage()
    {
        Storage::fake('public');

        $product = Product::factory()->create();

        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'category_id' => $category->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
        ];

        $response = $this->put('/api/products/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJson($data);

        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test the update method of ProductController without image update.
     *
     * @return void
     */
    public function testUpdateWithoutImage()
    {
        $product = Product::factory()->create();

        $category = Category::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'category_id' => $category->id,
        ];

        $response = $this->put('/api/products/' . $product->id, $data);

        $response->assertStatus(200)
            ->assertJson($data);

        $this->assertDatabaseHas('products', $data);
    }

    /**
     * Test the destroy method of ProductController.
     *
     * @return void
     */
    public function testDestroy()
    {
        $product = Product::factory()->create();

        $response = $this->delete('/api/products/' . $product->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Test the storeBulk method of ProductController.
     *
     * @return void
     */
    public function testStoreBulk()
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $data = [
            'products' => [
                [
                    'name' => $this->faker->name,
                    'description' => $this->faker->sentence,
                    'price' => $this->faker->randomFloat(2, 0, 100),
                    'category_id' => $category->id,
                    'image' => UploadedFile::fake()->image('product1.jpg'),
                ],
                [
                    'name' => $this->faker->name,
                    'description' => $this->faker->sentence,
                    'price' => $this->faker->randomFloat(2, 0, 100),
                    'category_id' => $category->id,
                    'image' => UploadedFile::fake()->image('product2.jpg'),
                ],
            ],
        ];

        $response = $this->post('/api/products/bulk', $data);

        $response->assertStatus(201)
            ->assertJson($data['products']);

        foreach ($data['products'] as $productData) {
            $this->assertDatabaseHas('products', $productData);
        }
    }
}