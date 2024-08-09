<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Model
{
    use HasFactory, hasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'department',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
