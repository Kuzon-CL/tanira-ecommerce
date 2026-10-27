<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->category = Category::create(['name' => 'Kopi Bubuk', 'slug' => 'kopi-bubuk']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Tanira House Blend',
            'sku' => 'TNR-HB-200',
            'category_id' => $this->category->id,
            'description' => 'Cokelat dan karamel.',
            'price' => 85000,
            'stock' => 20,
            'is_active' => '1',
        ], $overrides);
    }

    public function test_product_index_filters_by_search_and_category(): void
    {
        $other = Category::create(['name' => 'Merchandise', 'slug' => 'merchandise']);

        Product::create($this->payload(['name' => 'House Blend', 'sku' => 'HB-1']));
        Product::create($this->payload(['name' => 'Dark Roast', 'sku' => 'DR-1', 'category_id' => $other->id]));

        $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['q' => 'House']))
            ->assertOk()
            ->assertSee('House Blend')
            ->assertDontSee('Dark Roast');

        $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['category' => $other->id]))
            ->assertOk()
            ->assertSee('Dark Roast')
            ->assertDontSee('House Blend');
    }

    public function test_admin_can_create_product_with_image(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $this->payload([
            'image' => UploadedFile::fake()->image('kopi.jpg'),
        ]));

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'TNR-HB-200')->firstOrFail();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_validation_rejects_negative_price_and_duplicate_sku(): void
    {
        Product::create($this->payload());

        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $this->payload([
                'price' => -1,
            ]))
            ->assertSessionHasErrors(['sku', 'price']);

        $this->assertSame(1, Product::count());
    }

    public function test_update_without_new_image_keeps_existing_photo(): void
    {
        Storage::disk('public')->put('products/old.jpg', 'fake');

        $product = Product::create($this->payload(['image_path' => 'products/old.jpg']));

        $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), $this->payload(['name' => 'Nama Baru']))
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Nama Baru', $product->name);
        $this->assertSame('products/old.jpg', $product->image_path);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::create($this->payload());

        $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_pagination_preserves_query_string(): void
    {
        for ($i = 0; $i < 12; $i++) {
            Product::create($this->payload([
                'name' => 'Produk '.$i,
                'sku' => 'SKU-'.$i,
            ]));
        }

        $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['q' => 'Produk', 'page' => 2]))
            ->assertOk()
            ->assertSee('q=Produk', false);
    }
}
