<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'post_category_id', 'likes', 'views', 'is_approved'];
    protected $hidden = ['user_id', 'likes', 'views'];
    protected $casts = ['is_approved' => 'boolean'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Post_Category::class, 'post_category_id');
    }
}
