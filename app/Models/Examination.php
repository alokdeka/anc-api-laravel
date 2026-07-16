<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Examination extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title', 'program_code', 'exam_date', 'exam_time', 'center',
        'year', 'semester', 'instructions', 'is_active',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('timetable')->useDisk('public');
        $this->addMediaCollection('guidelines')->useDisk('public');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('exam_date', 'desc');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now())->where('is_active', true);
    }
}
