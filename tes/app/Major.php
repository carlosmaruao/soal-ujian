<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['title', 'initial', 'slug', 'active'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function infos()
    {
        return $this->hasMany(Info::class);
    }
    public function skors()
    {
        return $this->hasMany(Skor::class);
    }
}
