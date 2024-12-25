<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Tag;
use App\Models\VideoTag;

class VideoController extends Controller
{
    // Upload video
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'thumbnail_url' => 'nullable|url',
            'creator_id' => 'required|exists:Users,UserID',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $video = Video::create([
            'Title' => $request->title,
            'Description' => $request->description,
            'URL' => $request->url,
            'ThumbnailURL' => $request->thumbnail_url,
            'CreatorID' => $request->creator_id,
        ]);

        // Handle tags
        if ($request->tags) {
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['TagName' => $tagName]);
                VideoTag::create(['VideoID' => $video->VideoID, 'TagID' => $tag->TagID]);
            }
        }

        return response()->json([
            'message' => 'Video uploaded successfully',
            'video' => $video,
        ], 201);
    }

    // Edit video
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'url' => 'sometimes|url',
            'thumbnail_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $video->update($request->only(['title', 'description', 'url', 'thumbnail_url']));

        // Update tags
        if ($request->tags) {
            VideoTag::where('VideoID', $video->VideoID)->delete();

            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['TagName' => $tagName]);
                VideoTag::create(['VideoID' => $video->VideoID, 'TagID' => $tag->TagID]);
            }
        }

        return response()->json([
            'message' => 'Video updated successfully',
            'video' => $video,
        ]);
    }

    // Delete video
    public function delete($id)
    {
        $video = Video::findOrFail($id);

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }
}
