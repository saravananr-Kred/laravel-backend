<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{

    protected $table = 'licenses';
    protected $fillable = [
        'user_id',
        'license_number',
        'license_start_date',
        'license_end_date',
        'license_city',
        'license_state',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
