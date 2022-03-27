<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;
    // use \Znck\Eloquent\Traits\BelongsToThrough;
    protected $fillable = [
        'name', 'state', 'description'
    ];

    // public function progres()
    // {
    //     return $this->hasMany(Progres::class);

    // }
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'user_id', 'course_id');
 
    }

}
