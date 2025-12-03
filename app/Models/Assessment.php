<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_cycle_id',
        'user_id',
        'status',
        'overall_score',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'overall_score' => 'decimal:2',
            'completed_at' => 'datetime',
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

    public function items(): HasMany
    {
        return $this->hasMany(AssessmentItem::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    public function isPendingSelf(): bool
    {
        return $this->status === 'pending_self';
    }

    public function isPendingManager(): bool
    {
        return $this->status === 'pending_manager';
    }

    public function isPending360(): bool
    {
        return $this->status === 'pending_360';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Calculate overall score from items
     */
    public function calculateOverallScore(): float
    {
        $items = $this->items()->with('kpiAssignment')->get();
        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($items as $item) {
            if ($item->final_score !== null && $item->kpiAssignment) {
                $weight = (float) $item->kpiAssignment->weight;
                $totalWeightedScore += (float) $item->final_score * $weight;
                $totalWeight += $weight;
            }
        }

        if ($totalWeight == 0) {
            return 0;
        }

        return round($totalWeightedScore / $totalWeight, 2);
    }
}
