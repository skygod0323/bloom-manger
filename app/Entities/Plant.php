<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Plant extends Model
{
    protected $table = "plants";
    // public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}