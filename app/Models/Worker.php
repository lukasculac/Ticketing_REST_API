<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        ];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function files(){
        return $this->hasMany(File::class);
    }
}
