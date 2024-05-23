<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'department',
        'message',
        'status',
        'priority',
        'opened_at',
        'closed_at',
    ];

    public function worker(){
        return $this->belongsTo(Worker::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    protected static function booted()
    {
        static::deleting(function ($ticket) {
            $ticket->files()->delete();
        });
    }
}
