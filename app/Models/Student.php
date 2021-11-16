<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "rfid_number",
        "email",
        "username",
        "password",
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function class_details(){
        return $this->hasMany(ClassDetailStudent::class);
    }

    
}
