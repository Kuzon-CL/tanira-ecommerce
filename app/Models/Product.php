<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['category_id', 'name', 'sku', 'description', 'price', 'stock', 'image_path', 'is_active'])]
class Product extends Model
{
    use HasFactory;

    public const LOW_STOCK_THRESHOLD = 10;

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<OrderItem, $this> */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @param Builder<Product> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param Builder<Product> $query */
    public function scopeFilter(Builder $query, ?string $search, ?int $categoryId): void
    {
        $query->when($search, function (Builder $q, string $term): void {
            $q->where(function (Builder $inner) use ($term): void {
                $inner->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        })->when($categoryId, fn (Builder $q, int $id) => $q->where('category_id', $id));
    }

    public function stockStatus(): string
    {
        return match (true) {
            $this->stock <= 0 => 'habis',
            $this->stock <= self::LOW_STOCK_THRESHOLD => 'menipis',
            default => 'tersedia',
        };
    }

    public function stockStatusLabel(): string
    {
        return match ($this->stockStatus()) {
            'habis' => 'Habis',
            'menipis' => 'Stok Menipis',
            default => 'Tersedia',
        };
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
