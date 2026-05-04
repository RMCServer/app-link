<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'account_id',
        'name',
        'slug',
    ];

    public function scopeForAccount(Builder $query, int $accountId): Builder
    {
        return $query->where('account_id', $accountId);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function savedItems(): BelongsToMany
    {
        return $this->belongsToMany(SavedItem::class)
            ->withTimestamps();
    }
}
