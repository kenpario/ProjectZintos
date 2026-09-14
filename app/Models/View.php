<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    protected $table = 'post_views';
    protected $fillable = ['post_id', 'user_id'];
    protected $hidden = ['user_id', 'post_id'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }

    public function post(): BelongsTo
    {
        return $this->BelongsTo(Post::class, 'post_id');
    }
}
