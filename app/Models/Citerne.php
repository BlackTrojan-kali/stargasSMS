<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citerne extends Model
{
    use HasFactory;
    public function stock(){
        return $this->hasOne(Vracstock::class,"citerne_id");
    }
}
