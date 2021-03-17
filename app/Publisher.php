<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $guarded = [];  

    public function books(){
        return $this->hasMany(Book::class);
    }
}
