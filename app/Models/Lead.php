<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
    'uuid',
    'name',
    'email',
    'phone',
    'service',
    'message',
    'calculator_summary',
    'status',
    'manager_note',
    'contacted_at',
    'done_at',
    'source',
];

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
            'done_at' => 'datetime',
        ];
    }
}