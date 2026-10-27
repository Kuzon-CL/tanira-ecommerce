<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_sees_stats_from_database(): void
    {
        $admin = $this->admin();
        User::factory()->count(3)->create(['role' => User::ROLE_CUSTOMER]);

        $category = Category::create(['name' => 'Kopi Bubuk', 'slug' => 'kopi-bubuk']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'House Blend',
            'sku' => 'HB-1',
            'price' => 85000,
            'stock' => 5,
            'is_active' => true,
        ]);

        Order::create([
            'order_number' => 'INV-1',
            'customer_name' => 'Budi',
            'customer_email' => 'budi@example.com',
            'subtotal' => 85000,
            'total' => 100000,
            'payment_status' => 'pending',
            'order_status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('customerCount', 3)
            ->assertViewHas('orderCount', 1)
            ->assertViewHas('productCount', 1)
            ->assertViewHas('pendingPayment', 1);
    }
}
