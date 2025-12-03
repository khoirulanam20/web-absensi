<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'kpi_assignment_id',
        'self_score',
        'manager_score',
        'reviewer_score',
        'final_score',
        'evidence',
        'comment',
        'manager_comment',
    ];

    protected function casts(): array
    {
        return [
            'self_score' => 'decimal:2',
            'manager_score' => 'decimal:2',
            'reviewer_score' => 'decimal:2',
            'final_score' => 'decimal:2',
            'evidence' => 'array',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function kpiAssignment(): BelongsTo
    {
        return $this->belongsTo(KpiAssignment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(AssessmentReview::class);
    }

    /**
     * Calculate final score based on weights
     * Formula: final = (self * 20%) + (manager * 60%) + (peer * 20%)
     */
    public function calculateFinalScore(): float
    {
        $selfWeight = 0.20;
        $managerWeight = 0.60;
        $peerWeight = 0.20;

        $selfScore = (float) ($this->self_score ?? 0);
        $managerScore = (float) ($this->manager_score ?? 0);
        
        // Calculate average peer score if exists
        $peerScores = $this->reviews()->where('role', 'peer')->pluck('score')->filter();
        $peerScore = $peerScores->isNotEmpty() ? $peerScores->avg() : (float) ($this->reviewer_score ?? 0);

        $finalScore = ($selfScore * $selfWeight) + ($managerScore * $managerWeight) + ($peerScore * $peerWeight);
        
        return round($finalScore, 2);
    }
}
