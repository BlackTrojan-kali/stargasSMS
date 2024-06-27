<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public function hasStock(){
        return $this->hasMany(Stock::class,"article_id","id");
    }
}
