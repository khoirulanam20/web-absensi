<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'description',
        'category',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'current_value',
        'status',
        'location',
        'assigned_to',
        'assigned_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'assigned_date' => 'date',
            'purchase_price' => 'decimal:2',
            'current_value' => 'decimal:2',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function generateAssetCode(): string
    {
        $latestAsset = self::latest()->first();
        $lastNumber = $latestAsset ? (int) substr($latestAsset->asset_code, -4) : 0;
        $newNumber = $lastNumber + 1;
        return 'AST-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
