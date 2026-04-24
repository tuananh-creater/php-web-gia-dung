<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'summary',
        'content',
        'status',
    ];

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}