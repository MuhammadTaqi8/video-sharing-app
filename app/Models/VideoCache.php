<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCache extends Model
{
    use HasFactory;

    protected $table = 'VideoCache';

    protected $fillable = [
        'VideoID', 'CachedAt',
    ];

    public $timestamps = false; // This table does not have timestamps
}
