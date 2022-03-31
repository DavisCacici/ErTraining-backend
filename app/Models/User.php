<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;


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
        'token',
        'role_id',
        'last_request',
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

    public function createToken()
    {
        // $this->token = Hash::make(Str::random(10).$this->email.Str::random(10));
        // $this->save();
        $token = array(
            'data'=>array(
                "id" => $this->id,
                "user_name" => $this->user_name,
                "email" => $this->email,
                "role" => $this->role_id,
            )
        );
        $this->token = JWT::encode($token, env('SECRET_KEY'), "HS256");
        $this->save();
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function revoke()
    {
        $this->token = null;
        $this->save();
    }

    // public function progres()
    // {
    //     return $this->hasMany(Progres::class);

    // }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'course_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
