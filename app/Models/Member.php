<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'membership_date',
    ];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
