<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'status',
        'priority',
        'assigned_to',
        'assigned_to_user_name',
        'notes',
        'end_date',
        'file_url',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
