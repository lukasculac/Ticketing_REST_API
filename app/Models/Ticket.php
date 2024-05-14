<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function file()
    {
        return $this->hasMany(File::class);
    }
}
