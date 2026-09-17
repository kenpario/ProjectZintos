<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    /**
     * Create a new class instance.
     */
    protected $fillable = ['user_id', 'name', 'description'];
    protected $hidden = ['user_id', 'is_admin', 'is_mod', 'is_premium'];
    protected $casts = ['is_admin' => 'boolean', 'is_mod' => 'boolean', 'is_premium' => 'boolean'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
