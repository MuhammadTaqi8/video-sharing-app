<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    use HasFactory;

    protected $table = 'VideoViews';

    protected $fillable = [
        'VideoID', 'UserID', 'ViewedAt',
    ];

    // Relationship to the Video model
    public function video()
    {
        return $this->belongsTo(Video::class, 'VideoID');
    }

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
