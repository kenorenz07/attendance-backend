<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        "room_id",
        "subject_id",
        "schedule_id",
        "teacher_id"
    ];

    protected $with = ["room","subject","schedule","teacher","students"] ;

    protected $appends = ["student_count"];

    public function getStudentCountAttribute(){
        return $this->students()->count();
    }
    
    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    
    public function students(){
        return $this->hasMany(ClassDetailStudent::class);
    }
}
