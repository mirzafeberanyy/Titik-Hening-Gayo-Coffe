<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = ['title', 'subtitle', 'story', 'main_image', 'vision', 'mission', 'stats'];

    protected $casts = [
        'stats' => 'array',
    ];
}
