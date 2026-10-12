<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulletinController extends Controller
{
    public function index()
    {
        $bulletins = Bulletin::orderBy('published_date', 'desc')->paginate(10);
        return view('bulletins.index', compact('bulletins'));
    }

    public function download(Bulletin $bulletin)
    {
        $ext = pathinfo($bulletin->file_path, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($bulletin->file_path, $bulletin->title . '.' . $ext);
    }

    public function show(Bulletin $bulletin)
    {
        abort_unless(strtolower(pathinfo($bulletin->file_path, PATHINFO_EXTENSION)) === 'pdf', 404);

        return view('bulletins.show', compact('bulletin'));
    }

    public function preview(Bulletin $bulletin)
    {
        abort_unless(strtolower(pathinfo($bulletin->file_path, PATHINFO_EXTENSION)) === 'pdf', 404);

        $stream = Storage::disk('public')->readStream($bulletin->file_path);
        abort_unless(is_resource($stream), 404);

        $filename = \Illuminate\Support\Str::slug($bulletin->title).'.pdf';

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    // Admin Methods
    public function adminIndex()
    {
        $bulletins = Bulletin::orderBy('published_date', 'desc')->paginate(20);
        return view('admin.bulletins.index', compact('bulletins'));
    }

    public function adminPreview(Bulletin $bulletin)
    {
        abort_unless(strtolower(pathinfo($bulletin->file_path, PATHINFO_EXTENSION)) === 'pdf', 404);

        return view('admin.bulletins.preview', compact('bulletin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,jpg,jpeg,png,webp|max:10240', // 10MB limit
            'published_date' => 'required|date',
        ]);

        $path = $request->file('file')->storePublicly('bulletins', 'public');

        Bulletin::create([
            'title' => $validated['title'],
            'file_path' => $path,
            'published_date' => $validated['published_date'],
        ]);

        LogService::log('upload_bulletin', null, ['title' => $validated['title'], 'published_date' => $validated['published_date']]);
        return back()->with('success', 'Bulletin uploaded successfully.');
    }

    public function destroy(Bulletin $bulletin)
    {
        LogService::log('delete_bulletin', $bulletin, ['title' => $bulletin->title]);
        Storage::disk('public')->delete($bulletin->file_path);
        $bulletin->delete();
        return back()->with('success', 'Bulletin deleted successfully.');
    }
}
