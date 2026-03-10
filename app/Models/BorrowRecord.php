<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'issue_date',
        'return_date',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
