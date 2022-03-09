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
        "section_id",
        "teacher_id",
        "start_date",
        "end_date",
    ];

    protected $with = ["room","subject","schedule","section","teacher","students"] ;

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

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    
    public function students(){
        return $this->hasMany(ClassDetailStudent::class);
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }
}
