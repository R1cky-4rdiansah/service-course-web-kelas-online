<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $table = "lessons";

    protected $casts = [
        "created_at" => "datetime:Y-m-d H:m:s",
        "updated_at" => "datetime:Y-m-d H:m:s",
    ];

    protected $fillable = [
        "name", "video", "chapter_id"
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
