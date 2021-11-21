<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password','name','is_super'
    ];

    protected $appends = ['image_path'];


    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getImagePathAttribute()
    {
        return $this->image()->first() ? '/storage/'.$this->image()->first()->name : null;
    }
}