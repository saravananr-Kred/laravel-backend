<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'ISBN',
        'category',
        'published_year',
        'available_copies',
    ];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
