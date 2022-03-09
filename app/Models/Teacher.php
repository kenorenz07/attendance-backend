<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Teacher extends Authenticatable
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
    
    protected $appends = ['image_path',"full_name","display_name"];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getFullnameAttribute()
    {
        $fullname = '';
        if ($this->first_name) $fullname = ucfirst($this->first_name);
        if ($this->middle_name) $fullname .= ' '. ucfirst($this->middle_name);
        if ($this->last_name) $fullname .= ' '.ucfirst($this->last_name);
        return $fullname;
    }

    public function getDisplaynameAttribute()
    {
        $fullname = '';
        if ($this->first_name) $fullname = ucfirst($this->first_name);
        if ($this->last_name) $fullname .= ' '.ucfirst($this->last_name);
        return $fullname.' ('.$this->email.')';
    }

    public function getImagePathAttribute()
    {
        return $this->image()->first() ? '/storage/'.$this->image()->first()->name : null;
    }

    public function class_details()
    {
        return $this->hasMany(ClassDetail::class);
    }

    public function notifications(){
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
