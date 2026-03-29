<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images'; // Ensure this matches your migration

    protected $fillable = [
        'file_path',   // Path to the stored image (e.g., 'captures/capture_xyz.jpg')
        'cage',        // Optional: Which cage it came from (if relevant)
        'count',       // Optional: Fish count detected
        'user_id',     // Optional: Who captured it (if you're tracking users)
        'group_id',    // Daily group ID
        'capture_date', // Date of capture (YYYY-MM-DD)
    ];

    /**
     * Relationship: an image belongs to a user (optional).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
