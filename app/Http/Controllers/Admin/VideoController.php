<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderByDesc('created_at')->get();
        return view('admin.video.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.video.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'video_url' => 'nullable|string|max:500',
            'video_file' => 'nullable|mimes:mp4,mov,avi,wmv,flv,mkv,webm|max:102400',
            'description' => 'nullable|string|max:600',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['title', 'video_url', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file')->store('videos/files', 'public');
            $data['video_url'] = '-'; // Set to dash since column is NOT nullable
        }

        Video::create($data);

        return redirect()->route('admin.video.index')
            ->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video)
    {
        return view('admin.video.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'video_url' => 'nullable|string|max:500',
            'video_file' => 'nullable|mimes:mp4,mov,avi,wmv,flv,mkv,webm|max:102400',
            'description' => 'nullable|string|max:600',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['title', 'video_url', 'description']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        if ($request->hasFile('video_file')) {
            // Delete old video file if exists
            if ($video->video_file) {
                Storage::disk('public')->delete($video->video_file);
            }
            $data['video_file'] = $request->file('video_file')->store('videos/files', 'public');
            $data['video_url'] = '-'; // Set to dash since column is NOT nullable
        }

        $video->update($data);

        return redirect()->route('admin.video.index')
            ->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video)
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        if ($video->video_file) {
            Storage::disk('public')->delete($video->video_file);
        }

        $video->delete();

        return redirect()->route('admin.video.index')
            ->with('success', 'Video berhasil dihapus.');
    }
}
