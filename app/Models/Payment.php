<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['order_id', 'method', 'amount', 'proof_path', 'status', 'reference', 'paid_at', 'verified_at', 'verified_by'])]
class Payment extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu Verifikasi',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<User, $this> */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'bank_transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            default => ucfirst(str_replace('_', ' ', $this->method)),
        };
    }

    public function proofUrl(): ?string
    {
        if (! $this->proof_path) {
            return null;
        }

        if (Str::startsWith($this->proof_path, ['http://', 'https://'])) {
            return $this->proof_path;
        }

        return Storage::disk('public')->url($this->proof_path);
    }
}
