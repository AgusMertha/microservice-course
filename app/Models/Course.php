<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $casts = [
        "created_at" => "datetime: Y-m-d H:i:s",
        "updated_at" => "datetime: Y-m-d H:i:s"
    ];
    protected $fillable = ['name', 'certificate', 'thumbnail', 'type', 'status', 'price', 'level', 'description', 'mentor_id'];

    public function mentor()
    {
        return $this->belongsTo('App\Models\Mentor');
    }

    public function chapter()
    {
        return $this->hasMany('App\Models\Chapter')->orderBy('id', 'ASC');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ImageCourse')->orderBy('id', 'DESC');
    }
}
