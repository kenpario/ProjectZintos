<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post_Category extends Model
{
    protected $table = 'post_categories';
    protected $fillable = ['user_id','name','description'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }
}
