<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class statuses extends Model
{
    use HasFactory;

    public $timestamps = false;  // Désactive les timestamps

    protected $fillable = [
        "name",
        "value"
    ];

    // public function users()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function profil()
    {
        return $this->hasMany(profil::class);
    }
}
