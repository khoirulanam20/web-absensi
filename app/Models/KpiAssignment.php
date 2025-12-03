<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_cycle_id',
        'user_id',
        'kpi_id',
        'target',
        'weight',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'target' => 'decimal:2',
            'weight' => 'decimal:2',
        ];
    }

    public function reviewCycle(): BelongsTo
    {
        return $this->belongsTo(ReviewCycle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }

    public function assessmentItems(): HasMany
    {
        return $this->hasMany(AssessmentItem::class);
    }
}
