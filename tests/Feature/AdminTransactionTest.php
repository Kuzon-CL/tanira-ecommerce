<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    private function order(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'INV-2026-0001',
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'subtotal' => 85000,
            'shipping_cost' => 15000,
            'total' => 100000,
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
        ], $overrides));
    }

    public function test_transaction_index_filters_by_status_and_search(): void
    {
        $this->order();
        $this->order([
            'order_number' => 'INV-2026-0002',
            'customer_name' => 'Sari',
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.transactions.index', ['q' => 'Sari']))
            ->assertOk()
            ->assertSee('INV-2026-0002')
            ->assertDontSee('INV-2026-0001');

        $this->actingAs($this->admin)
            ->get(route('admin.transactions.index', ['payment_status' => 'unpaid']))
            ->assertOk()
            ->assertSee('INV-2026-0001')
            ->assertDontSee('INV-2026-0002');
    }

    public function test_transaction_detail_shows_customer_and_items(): void
    {
        $order = $this->order();
        $order->items()->create([
            'product_name' => 'House Blend',
            'sku' => 'HB-1',
            'unit_price' => 85000,
            'quantity' => 1,
            'line_total' => 85000,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.transactions.show', $order))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('House Blend');
    }

    public function test_admin_can_update_status(): void
    {
        $order = $this->order();

        $this->actingAs($this->admin)
            ->patch(route('admin.transactions.updateStatus', $order), [
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ])
            ->assertRedirect(route('admin.transactions.show', $order));

        $order->refresh();
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('processing', $order->order_status);
    }

    public function test_invalid_status_is_rejected_server_side(): void
    {
        $order = $this->order();

        $this->actingAs($this->admin)
            ->patch(route('admin.transactions.updateStatus', $order), [
                'payment_status' => 'bogus',
                'order_status' => 'pending',
            ])
            ->assertSessionHasErrors('payment_status');

        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }
}
