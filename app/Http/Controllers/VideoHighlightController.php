<?php

namespace App\Http\Controllers;

use App\Models\VideoHighlight;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoHighlightController extends Controller
{
    public function index()
    {
        $highlights = VideoHighlight::orderBy('sort_order', 'asc')->get();
        return view('admin.gallery.highlights.index', compact('highlights'));
    }

    public function show(VideoHighlight $highlight)
    {
        return view('admin.gallery.highlights.show', compact('highlight'));
    }

    public function create()
    {
        return view('admin.gallery.highlights.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogv,webm|max:102400|required_without:video_url',
            'video_url' => 'nullable|string|max:500|required_without:video_file',
            'is_published' => 'boolean',
        ]);

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->storeAs('highlights', $filename);
            if (! $path) {
                return back()->withErrors(['video_file' => 'The uploaded video could not be saved.'])->withInput();
            }
            $videoUrl = $filename; // Store the filename/path
        }

        VideoHighlight::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoUrl,
            'is_published' => $request->has('is_published'),
            'sort_order' => VideoHighlight::count(),
        ]);

        LogService::log('create_highlight', null, ['title' => $request->title]);
        Cache::forget('gallery_highlights');
        return redirect()->route('admin.highlights.index')->with('success', 'Video Highlight created successfully!');
    }

    public function edit(VideoHighlight $highlight)
    {
        return view('admin.gallery.highlights.edit', compact('highlight'));
    }

    public function update(Request $request, VideoHighlight $highlight)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogv,webm|max:102400',
            'video_url' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            // Delete old file if exists (using the raw database value)
            $oldPath = $highlight->getRawOriginal('video_path');
            if ($oldPath && !Str::startsWith($oldPath, ['http', 'www'])) {
                Storage::disk(config('filesystems.default'))->delete('highlights/' . $oldPath);
            }

            $file = $request->file('video_file');
            $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->storeAs('highlights', $filename);
            if (! $path) {
                return back()->withErrors(['video_file' => 'The uploaded video could not be saved.'])->withInput();
            }
            $videoUrl = $filename;
        }

        $highlight->update([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoUrl ?: $highlight->getRawOriginal('video_path'),
            'is_published' => $request->has('is_published'),
        ]);

        LogService::log('update_highlight', $highlight, ['title' => $highlight->title]);
        Cache::forget('gallery_highlights');
        return redirect()->route('admin.highlights.index')->with('success', 'Video Highlight updated successfully!');
    }

    public function destroy(VideoHighlight $highlight)
    {
        LogService::log('delete_highlight', $highlight, ['title' => $highlight->title]);
        $oldPath = $highlight->getRawOriginal('video_path');
        if ($oldPath && !Str::startsWith($oldPath, ['http', 'www'])) {
            Storage::disk(config('filesystems.default'))->delete('highlights/' . $oldPath);
        }
        $highlight->delete();
        Cache::forget('gallery_highlights');
        return back()->with('success', 'Video Highlight deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $orders = $request->orders;
        foreach ($orders as $id => $order) {
            VideoHighlight::where('id', $id)->update(['sort_order' => $order]);
        }
        LogService::log('reorder_highlights', null, ['order' => $orders]);
        return response()->json(['status' => 'success']);
    }
}
