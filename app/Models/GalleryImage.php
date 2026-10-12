<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class GalleryImage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gallery_images';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'album_id',
        'title',
        'caption',
        'storage_path',
        'type',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    public function getUrlAttribute()
    {
        $diskName = config('filesystems.default');
        $path = 'gallery/' . $this->storage_path;
        $disk = Storage::disk($diskName);

        // Le disque "local" est privé : sa route /storage/{path} exige une URL signée.
        // URL relative (sans hôte) pour fonctionner quel que soit l'hôte/port (ex. 127.0.0.1:8000).
        if ($diskName === 'local') {
            return URL::temporarySignedRoute(
                'storage.local',
                now()->addDays(7),
                ['path' => $path],
                absolute: false
            );
        }

        return $disk->url($path);
    }

    /**
     * Alt text for public pages — never exposes the stored upload filename.
     */
    public function publicAlt(?GalleryAlbum $album = null): string
    {
        if ($this->caption) {
            return $this->caption;
        }

        $album ??= $this->album;
        if ($album?->title) {
            return 'Photo from ' . $album->title;
        }

        return 'Gallery photo';
    }

    /**
     * Optional caption for public overlay/lightbox (null when none set).
     */
    public function publicCaption(): ?string
    {
        return $this->caption ?: null;
    }
}
