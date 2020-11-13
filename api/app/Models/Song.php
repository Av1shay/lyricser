<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'writer', 'composers',
        'performers', 'published_at', 'text_filename',
        'text_file_format', 'stanzas_delimiter', 'upload_by'
    ];

    protected $casts = [
        'composers' => 'array',
        'performers' => 'array',
    ];
}
