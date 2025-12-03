<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'unit',
        'default_target',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_target' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Job titles yang menggunakan KPI ini
     */
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'kpi_job_title')
            ->withPivot('default_weight', 'default_target')
            ->withTimestamps();
    }

    /**
     * KPI assignments
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(KpiAssignment::class);
    }

    /**
     * Generate KPI code
     */
    public static function generateCode(): string
    {
        $latestKpi = self::latest()->first();
        $lastNumber = $latestKpi ? (int) substr($latestKpi->code, -4) : 0;
        $newNumber = $lastNumber + 1;
        return 'KPI-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
