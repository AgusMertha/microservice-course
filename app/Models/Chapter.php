<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'course_id'];
    protected $casts = [
        "created_at" => "datetime: Y-m-d H:i:s",
        "updated_at" => "datetime: Y-m-d H:i:s"
    ];
    
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson')->orderBy('id', 'ASC');
    }
}
