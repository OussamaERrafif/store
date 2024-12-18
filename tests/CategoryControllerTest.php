<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method of CategoryController.
     *
     * @return void
     */
    public function testIndex()
    {
        // Create some categories
        Category::factory()->count(3)->create();

        // Make a GET request to the index method
        $response = $this->get('/categories');

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the categories
        $response->assertJson(Category::all()->toArray());
    }

    /**
     * Test the store method of CategoryController.
     *
     * @return void
     */
    public function testStore()
    {
        // Make a POST request to the store method with category data
        $response = $this->post('/categories', [
            'name' => 'Test Category',
        ]);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response contains the created category
        $response->assertJson([
            'name' => 'Test Category',
        ]);
    }

    /**
     * Test the storeBulkCategories method of CategoryController.
     *
     * @return void
     */
    public function testStoreBulkCategories()
    {
        // Make a POST request to the storeBulkCategories method with category data
        $response = $this->post('/categories/bulk', [
            'categories' => [
                ['name' => 'Category 1'],
                ['name' => 'Category 2'],
                ['name' => 'Category 3'],
            ],
        ]);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response contains the created categories
        $response->assertJson([
            ['name' => 'Category 1'],
            ['name' => 'Category 2'],
            ['name' => 'Category 3'],
        ]);
    }

    /**
     * Test the show method of CategoryController.
     *
     * @return void
     */
    public function testShow()
    {
        // Create a category
        $category = Category::factory()->create();

        // Make a GET request to the show method with the category ID
        $response = $this->get('/categories/' . $category->id);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the category
        $response->assertJson($category->toArray());
    }

    /**
     * Test the update method of CategoryController.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Create a category
        $category = Category::factory()->create();

        // Make a PUT request to the update method with updated category data
        $response = $this->put('/categories/' . $category->id, [
            'name' => 'Updated Category',
        ]);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the updated category
        $response->assertJson([
            'name' => 'Updated Category',
        ]);
    }

    /**
     * Test the destroy method of CategoryController.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create a category
        $category = Category::factory()->create();

        // Make a DELETE request to the destroy method with the category ID
        $response = $this->delete('/categories/' . $category->id);

        // Assert that the response has a 204 status code
        $response->assertStatus(204);

        // Assert that the category is deleted from the database
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}