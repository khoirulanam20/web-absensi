<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_daily',
        'is_taxable',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_daily' => 'boolean',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function employeeSalaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
