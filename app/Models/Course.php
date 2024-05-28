<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $table = "courses";

    protected $casts = [
        "created_at" => "datetime:Y-m-d H:m:s",
        "updated_at" => "datetime:Y-m-d H:m:s",
    ];

    protected $fillable = [
        "name", "certificate", "thumbnail",
        "type", "status", "price", "level",
        "description", "mentor_id"
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function chapter(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy("id", "ASC");
    }

    public function images(): HasMany
    {
        return $this->hasMany(ImageCourse::class)->orderBy("id", "DESC");
    }
}
