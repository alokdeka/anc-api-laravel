<?php

namespace App\Models;

use App\Traits\RevalidatesFrontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Circular extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'category', 'content', 'summary',
        'is_published', 'published_at', 'author_id', 'is_important', 'sort_order', 'external_url',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_important' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Circular $circular) {
            if (empty($circular->slug)) {
                $circular->slug = Str::slug($circular->title);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('public');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('published_at', $year)
                     ->orWhereYear('created_at', $year);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('summary', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }
}
