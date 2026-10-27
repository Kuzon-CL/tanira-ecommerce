<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@tanira.test'],
            [
                'name' => 'Admin Tanira',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        $customers = collect([
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com'],
            ['name' => 'Sari Wulandari', 'email' => 'sari@example.com'],
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com'],
        ])->map(fn (array $data) => User::query()->updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
                'email_verified_at' => now(),
            ],
        ));

        $categories = collect(['Kopi Bubuk', 'Kopi Biji', 'Merchandise'])
            ->mapWithKeys(fn (string $name) => [
                $name => Category::query()->firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name],
                ),
            ]);

        $products = collect([
            ['name' => 'Tanira House Blend', 'sku' => 'TNR-HB-200', 'price' => 85000, 'stock' => 42, 'category' => 'Kopi Bubuk', 'description' => 'Cokelat, karamel, sedikit buah.'],
            ['name' => 'Tanira Single Origin', 'sku' => 'TNR-SO-200', 'price' => 95000, 'stock' => 7, 'category' => 'Kopi Bubuk', 'description' => 'Bunga, jeruk, aftertaste bersih.'],
            ['name' => 'Tanira Dark Roast', 'sku' => 'TNR-DR-200', 'price' => 90000, 'stock' => 0, 'category' => 'Kopi Bubuk', 'description' => 'Pekat, rempah, body penuh.'],
            ['name' => 'Tanira Beans 1kg', 'sku' => 'TNR-BN-1K', 'price' => 320000, 'stock' => 18, 'category' => 'Kopi Biji', 'description' => 'Biji kopi segar sangrai medium.'],
            ['name' => 'Tanira Tumbler', 'sku' => 'TNR-MR-TMB', 'price' => 150000, 'stock' => 25, 'category' => 'Merchandise', 'description' => 'Tumbler stainless 500ml.'],
        ])->map(fn (array $data) => Product::query()->updateOrCreate(
            ['sku' => $data['sku']],
            [
                'name' => $data['name'],
                'category_id' => $categories[$data['category']]->id,
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => true,
            ],
        ));

        $orders = [
            ['customer' => 0, 'items' => [0 => 2, 3 => 1], 'payment' => 'paid', 'order' => 'completed', 'proof' => true],
            ['customer' => 1, 'items' => [1 => 1], 'payment' => 'pending', 'order' => 'pending', 'proof' => true],
            ['customer' => 2, 'items' => [2 => 1, 4 => 2], 'payment' => 'unpaid', 'order' => 'pending', 'proof' => false],
            ['customer' => 0, 'items' => [0 => 1], 'payment' => 'paid', 'order' => 'shipped', 'proof' => true],
        ];

        foreach ($orders as $index => $data) {
            $customer = $customers[$data['customer']];
            $number = 'INV-'.now()->subDays($index)->format('Ymd').'-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

            $order = Order::query()->firstOrCreate(
                ['order_number' => $number],
                [
                    'user_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => '08120000000'.$index,
                    'shipping_address' => 'Jl. Contoh No. '.($index + 1).', Jakarta',
                    'subtotal' => 0,
                    'shipping_cost' => 15000,
                    'total' => 0,
                    'payment_status' => $data['payment'],
                    'order_status' => $data['order'],
                ],
            );

            if ($order->items()->exists()) {
                continue;
            }

            $subtotal = 0;
            foreach ($data['items'] as $productIndex => $quantity) {
                $product = $products[$productIndex];
                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + $order->shipping_cost,
            ]);

            if ($data['proof']) {
                Payment::create([
                    'order_id' => $order->id,
                    'method' => 'bank_transfer',
                    'amount' => $order->total,
                    'status' => $data['payment'] === 'paid' ? 'verified' : 'pending',
                    'paid_at' => $order->created_at,
                    'verified_at' => $data['payment'] === 'paid' ? now() : null,
                    'verified_by' => $data['payment'] === 'paid' ? User::where('role', User::ROLE_ADMIN)->value('id') : null,
                ]);
            }
        }
    }
}
