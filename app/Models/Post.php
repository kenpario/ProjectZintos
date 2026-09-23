<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'post_category_id', 'likes', 'views', 'is_approved', 'media', 'is_pinned'];
    protected $hidden = ['user_id', 'likes', 'views'];
    protected $casts = ['is_approved' => 'boolean', 'is_pinned' => 'boolean'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Post_Category::class, 'post_category_id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function like(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    protected static array $videoExtensions = ['mp4'];

    public function isVideo(): bool
    {
        if (! $this->media) {
            return false;
        }

        $extension = pathinfo($this->media, PATHINFO_EXTENSION);

        return in_array(strtolower($extension), static::$videoExtensions);
    }
}
