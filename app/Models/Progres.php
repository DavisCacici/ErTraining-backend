<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Progres extends Model
{
    use HasFactory;
    protected $fillable = [
        'step_id',
        'state',
        'course_user_id',
    ];

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    
}
