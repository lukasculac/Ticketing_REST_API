<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'path'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
