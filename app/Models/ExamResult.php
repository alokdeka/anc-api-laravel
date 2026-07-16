<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'examination_id', 'roll_number', 'candidate_name', 'registration_number',
        'marks', 'total_marks', 'percentage', 'result', 'rank', 'grade',
    ];

    protected $casts = [
        'marks'       => 'decimal:2',
        'total_marks' => 'decimal:2',
    ];

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function scopeByRollNumber($query, $rollNumber)
    {
        return $query->where('roll_number', $rollNumber);
    }

    public function scopePassed($query)
    {
        return $query->where('result', 'pass');
    }
}
