<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialLink extends Model
{
    use HasFactory;
    protected $table = 'user_social_links';
    protected $fillable = [
        'user_id',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'github_url',
    ];
}
