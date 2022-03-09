<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function class_detail(){
        return $this->belongsTo(ClassDetail::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

}
