<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 * required={"password"},
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="step_id", type="integer", example="1"),
 * @OA\Property(property="user_id", type="integer", example="1"),
 * @OA\Property(property="course_id", type="integer", example="1"),
 * @OA\Property(property="state", type="string", description="in corso"),
 * )
 *
 * Class Progress
 *
 */

class Progress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'step_id', 'state'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
