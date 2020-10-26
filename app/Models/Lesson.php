<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $casts = [
        "created_at" => "datetime: Y-m-d H:i:s",
        "updated_at" => "datetime: Y-m-d H:i:s"
    ];

    protected $fillable = ['name', 'video','chapter_id'];
}
