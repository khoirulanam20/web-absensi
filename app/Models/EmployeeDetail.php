<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'user_id',
        'nik',
        'npwp',
        'bpjs_tk',
        'bpjs_kes',
        'marital_status',
        'phone_emergency',
        'join_date',
        'end_contract_date',
        'employment_status',
        'bank_name',
        'bank_account_number',
    ];

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'end_contract_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
