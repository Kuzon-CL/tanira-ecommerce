<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
    'shipping_address', 'subtotal', 'shipping_cost', 'total',
    'payment_status', 'order_status', 'notes',
])]
class Order extends Model
{
    use HasFactory;

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum Dibayar',
        'pending' => 'Menunggu Verifikasi',
        'paid' => 'Dibayar',
        'failed' => 'Gagal',
        'refunded' => 'Dikembalikan',
    ];

    public const ORDER_STATUSES = [
        'pending' => 'Menunggu',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'shipping_cost' => 'integer',
            'total' => 'integer',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasMany<Payment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** @param Builder<Order> $query */
    public function scopeFilter(Builder $query, ?string $search, ?string $paymentStatus, ?string $orderStatus, ?string $date): void
    {
        $query->when($search, function (Builder $q, string $term): void {
            $q->where(function (Builder $inner) use ($term): void {
                $inner->where('order_number', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%");
            });
        })
            ->when($paymentStatus, fn (Builder $q, string $s) => $q->where('payment_status', $s))
            ->when($orderStatus, fn (Builder $q, string $s) => $q->where('order_status', $s))
            ->when($date, fn (Builder $q, string $d) => $q->whereDate('created_at', $d));
    }

    public function paymentStatusLabel(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? ucfirst($this->payment_status);
    }

    public function orderStatusLabel(): string
    {
        return self::ORDER_STATUSES[$this->order_status] ?? ucfirst($this->order_status);
    }
}
