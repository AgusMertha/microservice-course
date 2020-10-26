<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'rating', 'note'];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
