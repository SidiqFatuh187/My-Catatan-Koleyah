<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'user_id', 
        'category_id', 
        'title', 
        'description',
        'status', 
        'priority', 
        'deadline', 
        'completed_at',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'deadline'     => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
