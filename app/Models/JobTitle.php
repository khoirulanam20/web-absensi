<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $fillable = [
        'name'
    ];

    public function kpis()
    {
        return $this->belongsToMany(Kpi::class, 'kpi_job_title')
            ->withPivot('default_weight', 'default_target')
            ->withTimestamps();
    }
}
