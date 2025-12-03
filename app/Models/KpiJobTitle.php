<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiJobTitle extends Model
{
    use HasFactory;

    protected $table = 'kpi_job_title';

    protected $fillable = [
        'kpi_id',
        'job_title_id',
        'default_weight',
        'default_target',
    ];

    protected function casts(): array
    {
        return [
            'default_weight' => 'decimal:2',
            'default_target' => 'decimal:2',
        ];
    }

    public function kpi(): BelongsTo
    {
        return $this->belongsTo(Kpi::class);
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }
}
