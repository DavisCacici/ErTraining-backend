<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;
    protected $fillable = [
        'description'
    ];

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function userCourse()
    {
        return $this->belongsToMany(UserCourse::class);
    }
}
