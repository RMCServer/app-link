<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavedItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'category_id',
        'created_by_user_id',
        'type',
        'title',
        'description',
        'source_url',
        'final_url',
        'image_url',
        'favicon_url',
        'site_name',
        'provider_name',
        'file_path',
        'mime_type',
        'metadata',
        'is_favorite',
        'is_archived',
        'fetched_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_favorite' => 'boolean',
        'is_archived' => 'boolean',
        'fetched_at' => 'datetime',
    ];

    public function scopeForAccount(Builder $query, int $accountId): Builder
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeLinks(Builder $query): Builder
    {
        return $query->where('type', 'link');
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('type', 'image');
    }

    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('is_favorite', true);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    public function scopeNotArchived(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->withTimestamps();
    }

    public function getPreviewImageAttribute(): ?string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return $this->file_path
            ? route('saved-items.image', $this)
            : null;
    }

    public function getUrlAttribute(): ?string
    {
        return $this->final_url ?: $this->source_url;
    }
}
