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

    public function courseuser()
    {
        // return DB::select('select * from progres as p
        // inner join course_user as cu on cu.id = p.course_user_id');
        if($this->course_user_id)
        {
            return DB::select('select u.user_name, c.name from course_user as cu
            inner join courses as c on c.id = cu.course_id
            inner join users as u on u.id = cu.user_id
            where cu.id = ?', [$this->course_user_id]);
        }
        else{
            return DB::select('select u.user_name, c.name from course_user as cu
            inner join courses as c on c.id = cu.course_id
            inner join users as u on u.id = cu.user_id');
        }
        // return DB::select('select * from progres as p
        // inner join course_user as cu on cu.id = p.course_user_id
        // inner join course as c on c.id = cu.course_id
        // inner join user as u on c.id = cu.user_id');
        // return $this->course_user_id;
    }
}
