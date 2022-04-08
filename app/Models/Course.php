<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @OA\Schema(
 * required={"password"},
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="name", type="string", maxLength=32, example="Corso sicurezza"),
 * @OA\Property(property="state", type="string", example="attivo"),
 * @OA\Property(property="description", type="string", description="una descrizione del corso"),
 * )
 *
 * Class Course
 *
 */

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
        return $this->belongsToMany(User::class, 'progress', 'user_id', 'course_id');

    }

}
