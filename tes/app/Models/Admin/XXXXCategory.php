<?php

namespace App\Models\Admin;

use App\Major;
use App\Question;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['major_id', 'name', 'slug'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function question()
    {
        return $this->hasMany(Question::class);
    }
    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
