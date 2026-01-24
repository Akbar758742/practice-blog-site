<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_visible',
        'meta_title',
        'meta_desc',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
