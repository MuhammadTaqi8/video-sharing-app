<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    use HasFactory;

    protected $table = 'VideoTags';

    protected $fillable = [
        'VideoID', 'TagID',
    ];

    public $timestamps = false; // This table does not have timestamps

    // Relationships
    public function video()
    {
        return $this->belongsTo(Video::class, 'VideoID');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'TagID');
    }
}
