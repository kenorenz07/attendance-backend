<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    const ABSENT = 0;
    const PRESENT = 1;
    const LATE = 2;
    const EXCUSED = 3;
    // const  = 4;

    protected $fillable = ['remarks','time_in','time_out','date_of_attendance','class_detail_students_id'];

    public function class_detail_student(){
        return $this->belongsTo(ClassDetailStudent::class,'class_detail_students_id');
    }
}
