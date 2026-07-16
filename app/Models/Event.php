<?php

namespace App\Models;

use App\Traits\RevalidatesFrontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, RevalidatesFrontend;

    public function getRevalidatePaths()
    {
        return ['/calendar'];
    }
    
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
    ];
}
