<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;
    protected $fillable = ['make', 'model', 'year'];

    public function trips()
    {
        return $this->hasMany(Trips::class);
    }
}
