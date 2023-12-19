<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class candidatPostuler extends Model
{
    use HasFactory;
   

    public function candidat()
    {
        return $this->hasMany(Formations::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formations::class, 'formations_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
