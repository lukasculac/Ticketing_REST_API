<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Worker extends Model implements AuthenticatableContract
{
    use HasFactory, hasApiTokens, Authenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'position',
        ];
    protected $hidden = [
        'password',
    ];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function files(){
        return $this->hasMany(File::class);
    }

    protected static function booted()
    {
        static::deleting(function ($worker) {
            $worker->tickets()->delete();
        });
    }
}
