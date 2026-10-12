<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VideoHighlight extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_path',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getVideoUrlAttribute()
    {
        $value = $this->video_path;
        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        if (Str::startsWith($value, 'www.')) {
            return 'https://' . $value;
        }

        $path = 'highlights/' . ltrim((string) $value, '/');
        $diskName = config('filesystems.default');

        if ($diskName === 'local') {
            return URL::temporarySignedRoute(
                'storage.local',
                now()->addDays(7),
                ['path' => $path],
                absolute: false
            );
        }

        return Storage::disk($diskName)->url($path);
    }

    public function getThumbnailUrlAttribute()
    {
        $url = $this->video_url;

        if (preg_match('#(?:youtube\.com/(?:watch\?.*v=|embed/)|youtu\.be/)([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
            return 'https://vumbnail.com/' . $m[1] . '.jpg';
        }

        if (Str::startsWith($url, ['http', 'https', 'www'])) {
            return null;
        }

        return null;
    }

    public function getEmbedUrlAttribute()
    {
        $url = $this->video_url;

        if (preg_match('#youtube\.com/watch\?.*v=([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#youtu\.be/([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }

    public function getIsExternalAttribute()
    {
        return Str::startsWith($this->video_url, ['http://', 'https://']);
    }

    public function getVideoMimeTypeAttribute(): string
    {
        return match (strtolower(pathinfo((string) $this->video_path, PATHINFO_EXTENSION))) {
            'mov' => 'video/quicktime',
            'ogv' => 'video/ogg',
            'webm' => 'video/webm',
            default => 'video/mp4',
        };
    }
}
