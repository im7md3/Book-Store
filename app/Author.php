<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];  

    public function books(){
        return $this->belongsToMany(Book::class,'book_author');
    }
}
