<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'street',
        'city',
        'state',
        'pincode',
        'dob',
        'gender',
        'role',
        'profile_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
