<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Video;

class CommentController extends Controller
{
    // Add a comment to a video
    public function store(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $video = Video::findOrFail($id);

        $comment = new Comment();
        $comment->video_id = $video->id;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => $comment,
        ], 201);
    }

    // List all comments for a video
    public function index($id)
    {
        $video = Video::findOrFail($id);
        $comments = $video->comments()->with('user')->latest()->get();

        return response()->json($comments);
    }
}
