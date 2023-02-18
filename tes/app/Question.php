<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['category_id', 'major_id', 'title', 'slug', 'active'];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
