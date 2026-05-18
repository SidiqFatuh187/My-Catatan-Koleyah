<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\{Table, Fillable};

#[Fillable(['name', 'user_id', 'color', 'icon'])]
#[Table('category')]

class Category extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
//** Relasi dengan model Todo
   // public function todos()
  //  {
  //  return $this->hasMany(Todo::class);
  //  }
}
