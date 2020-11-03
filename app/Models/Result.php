<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function user_data()
    // {
    //     return $this->hasOne('App\Models\User',);
    // }

    public function user_data()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
