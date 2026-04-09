<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class audit_log extends Model
{
    protected $fillable=[
        'action',
        'module',
        'user_id',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
