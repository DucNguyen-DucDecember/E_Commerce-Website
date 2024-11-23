<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['post_title', 'post_slug', 'post_excerpt', 'post_content', 'post_status', 'post_thumb', 'user_id', 'category_id'];

    function post_category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }
}
