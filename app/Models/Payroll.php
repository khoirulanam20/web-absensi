<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period',
        'payment_date',
        'total_attendance',
        'basic_salary',
        'total_allowance',
        'total_deduction',
        'net_salary',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'total_attendance' => 'integer',
            'basic_salary' => 'decimal:2',
            'total_allowance' => 'decimal:2',
            'total_deduction' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
