<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $url
 * @property string|null $thumbnail_url
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Video extends Model
{
    use HasFactory;

    protected $table = 'Videos';

    protected $fillable = [
        'Title', 'Description', 'URL', 'ThumbnailURL', 'CreatorID',
    ];

    // Relationship to Creator (User)
    public function creator()
    {
        return $this->belongsTo(User::class, 'CreatorID');
    }

    // Comments for the video
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Ratings for the video
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Views for the video
    public function videoViews()
    {
        return $this->hasMany(VideoView::class);
    }

    // Tags for the video (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'VideoTags', 'VideoID', 'TagID');
    }
}
