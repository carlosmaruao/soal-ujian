<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skor extends Model
{
    protected $fillable = [
        'user_id',
        'major_id',
        'A1',
        'A2',
        'A3',
        'A4',
        'A5',
        'A6',
        'A7',
        'A8',
        'A9',
        'A10',
        'A11',
        'A12',
        'A13',
        'A14',
        'A15',
        'Point',
        'tanggal'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
