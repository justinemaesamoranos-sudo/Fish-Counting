<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_id',
        'manual',
        'ai',
    ];

    /* Relationships */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
