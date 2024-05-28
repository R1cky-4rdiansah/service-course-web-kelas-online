<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    use HasFactory;

    protected $table = "image_courses";

    protected $casts = [
        "created_at" => "datetime:Y-m-d H:m:s",
        "updated_at" => "datetime:Y-m-d H:m:s",
    ];

    protected $fillable = [
        "course_id", "image"
    ];
}
