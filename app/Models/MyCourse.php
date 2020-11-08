<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id'];
    protected $casts = [
        "created_at" => "datetime: Y-m-d H:i:s",
        "updated_at" => "datetime: Y-m-d H:i:s"
    ];
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
