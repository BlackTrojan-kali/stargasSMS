<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    public function Clientcat()
    {
        return $this->belongsTo(Clientcat::class, "id_clientcat", "id");
    }
    public function price()
    {
        return $this->hasMany(Clientprice::class);
    }
}
