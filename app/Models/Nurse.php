<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Nurse extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'registration_number', 'name', 'father_husband_name', 'dob', 'gender',
        'qualification', 'institute_id', 'registration_date', 'expiry_date',
        'status', 'address', 'district', 'state', 'mobile', 'email',
        'remarks', 'approved_by',
    ];

    protected $casts = [
        'dob'               => 'date',
        'registration_date' => 'date',
        'expiry_date'       => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile()->useDisk('public');
        $this->addMediaCollection('certificate')->useDisk('public');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRegistrationNumber($query, $regNo)
    {
        return $query->where('registration_number', $regNo);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
