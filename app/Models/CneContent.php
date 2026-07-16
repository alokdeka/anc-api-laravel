<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CneContent extends Model
{
    use HasFactory;

    protected $fillable = ['section', 'title', 'content'];

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }
}
