<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 *
 * @OA\Schema(
 * required={"password"},
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="user_name", type="string", maxLength=32, example="John"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="email_verified_at", type="string", readOnly="true", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 * @OA\Property(property="role", type="string", readOnly="true", description="User role"),
 * @OA\Property(property="acces_token", type="string", readOnly="true", description="token"),
 * @OA\Xml(name="access_token"),
 * )
 *
 * Class User
 *
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    // use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'progress', 'course_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function progress()
    {
        return $this->belongsTo(Progress::class);
    }

}
