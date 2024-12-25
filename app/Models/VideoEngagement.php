<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEngagement extends Model
{
    use HasFactory;

    protected $table = 'VideoEngagement';

    protected $fillable = [
        'VideoID', 'Views', 'Likes', 'Shares',
    ];

    // Relationship to the Video model
    public function video()
    {
        return $this->belongsTo(Video::class, 'VideoID');
    }
}
