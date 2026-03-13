<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'user_name',
        'comment',
    ];

    public function Task(){
        return $this->belongsTo(Task::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}
