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
}
