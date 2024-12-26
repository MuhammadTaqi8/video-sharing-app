<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Tag;
use App\Models\VideoTag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\VideoEngagement;
use App\Models\VideoCache;


class VideoController extends Controller
{
    // Upload video (same as previous code)
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'video' => 'required|file|mimes:mp4,mkv,avi|max:100000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $videoPath = $request->file('video')->store('videos', 'public');
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video = Video::create([
            'Title' => $request->title,
            'Description' => $request->description,
            'URL' => $videoPath,
            'ThumbnailURL' => $thumbnailPath,
            'CreatorID' => $request->creator_id,
        ]);

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

    // Edit video (same as previous code)
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:1000',
            'video' => 'nullable|file|mimes:mp4,mkv,avi|max:100000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        // Handle video file update
        if ($request->hasFile('video')) {
            Storage::disk('public')->delete($video->URL);
            $videoPath = $request->file('video')->store('videos', 'public');
            $video->URL = $videoPath;
        }

        // Handle thumbnail update
        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->delete($video->ThumbnailURL);
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $video->ThumbnailURL = $thumbnailPath;
        }

        $video->update($request->only(['title', 'description']));

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

    // Delete video (same as previous code)
    public function delete($id)
    {
        $video = Video::findOrFail($id);

        Storage::disk('public')->delete($video->URL);
        if ($video->ThumbnailURL) {
            Storage::disk('public')->delete($video->ThumbnailURL);
        }

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }

    // Get all videos (pagination)
    public function index(Request $request)
    {
        $videos = Video::paginate(10);  // Paginate the videos, 10 per page

        return response()->json($videos);
    }

    // Get a single video by ID
    public function show($id)
    {
        $video = Video::with('tags')->findOrFail($id);  // Load tags for the video as well

        return response()->json($video);
    }

    // Search videos by title, description, or tags
    public function search(Request $request)
    {
        $query = Video::query();

        // Search by title or description
        if ($request->has('q') && !empty($request->q)) {
            $query->where('Title', 'like', '%' . $request->q . '%')
                  ->orWhere('Description', 'like', '%' . $request->q . '%');
        }

        // Search by tags
        if ($request->has('tags') && !empty($request->tags)) {
            $tags = explode(',', $request->tags);  // Tags passed as comma-separated string
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('TagName', $tags);
            });
        }

        $videos = $query->paginate(10);  // Paginate search results

        return response()->json($videos);
    }

    public function like(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        // Check if the user has already liked the video
        $existingLike = VideoEngagement::where('VideoID', $video->VideoID)
            ->where('UserID', $request->user()->id)  // Assuming user is authenticated
            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this video.'], 400);
        }

        // Increment likes
        $video->engagement->increment('Likes');

        return response()->json(['message' => 'You liked the video successfully.']);
    }

    public function share(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        // Increment shares
        $video->engagement->increment('Shares');

        return response()->json(['message' => 'You shared the video successfully.']);
    }

    public function cacheTrendingVideos()
    {
        // Select videos with high views, likes, and shares
        $trendingVideos = Video::with('engagement')
            ->where('engagement.views', '>', 100)  // Example condition: More than 100 views
            ->orderBy('engagement.likes', 'desc')  // Sort by most likes
            ->take(10)  // Limit to top 10
            ->get();

        foreach ($trendingVideos as $video) {
            // Cache the trending video
            VideoCache::create([
                'VideoID' => $video->VideoID,
            ]);
        }

        return response()->json(['message' => 'Trending videos cached successfully.']);
    }


}
