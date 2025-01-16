<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $fillable = [
        "lastname",
        "firstname",
        "image",
        "statuses_id",
    ];

    // Realtion that says a profile can only have on status whether active, inactive or pending

    public function status()
    {
        return $this->belongsTo(statuses::class);
    }
}
