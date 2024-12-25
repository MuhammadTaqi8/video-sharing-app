<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'Ratings';

    protected $fillable = [
        'VideoID', 'UserID', 'Rating',
    ];

    // Relationship to the User model
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'UserID');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Video model
    public function video()
    {
        return $this->belongsTo(Video::class, 'VideoID');
    }
}
