<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function rate()
    {
        return $this->ratings->isNotEmpty() ? $this->ratings->sum('value') / $this->ratings->count() : 0;
    }
}
