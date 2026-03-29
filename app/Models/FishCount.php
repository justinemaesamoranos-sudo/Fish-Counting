<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishCount extends Model
{
    use HasFactory;

    // Optional: explicitly define table name if needed
    protected $table = 'fish_counts';

    // Allow mass assignment
    protected $fillable = ['count', 'recorded_at'];

    // Disable default timestamps (created_at / updated_at)
    public $timestamps = false;
}
