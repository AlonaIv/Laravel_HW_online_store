<?php

namespace Tests\Feature\Admin;

use App\Http\Requests\Admin\UpdateCategory;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategoryProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestResult;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    protected function afterRefreshingDatabase()
    {
        $this->seed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_allow_see_categories()
    {
        $categories = Category::orderByDesc('id')->paginate(10)->pluck('name')->toArray();
        $response = $this->actingAs($this->getUser())->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertSeeInOrder($categories);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_not_allow_see_categories()
    {
        $response = $this->actingAs($this->getUser('customer'))->get(route('admin.categories.index'));

        $response->assertStatus(403);
    }

    public function test_create_category_with_valid_data()
    {
        $data = array_merge(
            Category::factory()->make()->toArray(),
            ['parent_id' => Category::all()->random()?->id]
        );

        $response = $this->actingAs($this->getUser())
            ->post(
                route('admin.categories.store'),
                $data
            );

        $response->assertStatus(302);
//        $response->assertRedirectToSignedRoute('admin.categories.index');
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => $data['name']
        ]);
    }

    public function test_create_category_with_invalid_data()
    {
        $data = ['name' => 'D'];

        $response = $this->actingAs($this->getUser())
            ->post(
                route('admin.categories.store'),
                $data
            );

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'The name must be at least 2 characters.'
        ]);
    }
//////////////////mymymymmyymymmymymmy

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_allow_edit_categories()
    {
        $category = Category::factory()->make()->first();

        $response = $this->actingAs($this->getUser())
            ->get(
                route(
                    'admin.categories.edit',
                    ['categories' => Category::all(), 'category' => $category])
            );

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_customer_not_allow_edit_categories()
    {
        $category = Category::factory()->make()->first();

        $response = $this->actingAs($this->getUser('customer'))
            ->get(
                route(
                    'admin.categories.edit',
                    ['categories' => Category::all(), 'category' => $category])
            );

        $response->assertStatus(403);
    }

    public function test_update_category_with_valid_data()
    {
        $category = Category::factory()->make()->first();
        $data = ['parent_id' => $category?->parent_id, 'name' => 'TestNewName', 'description' => 'New Test Description to this Category'];

        $response = $this->actingAs($this->getUser())
            ->put(
                route('admin.categories.update',
                    ['category' => $category]),
                $data
            );

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.categories.edit', ['categories' => Category::all(), 'category' => $category]));
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_update_category_with_invalid_data()
    {
        $category = Category::factory()->make()->first();
        $data = ['parent_id' => $category?->parent_id, 'name' => 'T', 'description' => 'New Test Description to this Category2'];

        $response = $this->actingAs($this->getUser())
            ->put(
                route('admin.categories.update',
                    ['category' => $category]),
                $data
            );

        $response->assertStatus(302);
        $response->assertInvalid([
            'name' => 'The name must be at least 2 characters.'
        ]);
    }

    protected function getUser(string $role = 'admin')
    {
        return User::role($role)->first();
    }
}
