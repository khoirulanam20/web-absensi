<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory, HasTimestamps;

    protected $fillable = [
        'name',
        'quota',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quota' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
