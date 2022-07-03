<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Entities\Plant;

class Task extends Model
{
    protected $table = "tasks";
    // public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plant() {
        return $this->belongsTo(Plant::class, 'plant_id');
    }
}