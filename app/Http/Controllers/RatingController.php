<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Video;

class RatingController extends Controller
{
    // Add or update a rating for a video
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        $video = Video::findOrFail($id);

        $rating = Rating::updateOrCreate(
            [
                'video_id' => $video->id,
                'user_id' =>  $request->user()->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return response()->json([
            'message' => 'Rating submitted successfully.',
            'rating' => $rating,
        ]);
    }

    // Get the average rating for a video
    public function show($id)
    {
        $video = Video::findOrFail($id);
        $averageRating = $video->ratings()->avg('rating');

        return response()->json([
            'average_rating' => round($averageRating, 2),
        ]);
    }
}
