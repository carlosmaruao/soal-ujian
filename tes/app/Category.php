<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $fillable = ['major_id', 'name', 'slug', 'active'];
 
    public function questions()
    {
        return $this->hasMany(Question::class);
    } 

    public function activeQuestion() {
        return $this->questions()->where('active','=', 1);
    }

    public function xxx() {
        return $this->questions()->where('active','=', 1);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}