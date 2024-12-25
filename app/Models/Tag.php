<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'Tags';

    protected $fillable = [
        'TagName',
    ];

    // Many-to-Many relationship with Video (via VideoTags table)
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'VideoTags', 'TagID', 'VideoID');
    }
}
