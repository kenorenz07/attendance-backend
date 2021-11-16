<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDetailStudent extends Model
{
    use HasFactory;

    protected $fillable = ['class_detail_id','student_id'];
    
    public function class_detail(){
        return $this->belongsTo(ClassDetail::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,"class_detail_students_id");
    }
}
