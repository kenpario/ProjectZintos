<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'post_comments';
    protected $fillable = ['post_id', 'user_id', 'message', 'is_approved'];
    protected $hidden = ['user_id', 'post_id'];
    protected $casts = ['is_approved' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }

    public function post(): BelongsTo
    {
        return $this->BelongsTo(Post::class, 'post_id');
    }
}
