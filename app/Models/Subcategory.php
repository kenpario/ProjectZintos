<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    protected $table = 'post_subcategories';
    protected $fillable = ['user_id', 'name', 'description', 'post_category_id'];
    protected $hidden = ['user_id'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_subcategory_id');
    }
    public function category(): BelongsTo
    {
        return $this->BelongsTo(Post_Category::class, 'post_category_id');
    }
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id');
    }
}
