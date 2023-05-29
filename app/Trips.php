<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'miles', 'car_id'];

    public function car()
    {
        return $this->belongsTo(Cars::class);
    }
}
