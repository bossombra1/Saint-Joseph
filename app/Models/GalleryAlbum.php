<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class GalleryAlbum extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'gallery_albums';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'featured_video_url',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'album_id')->where('is_published', true);
    }

    public function coverImage()
    {
        return $this->hasOne(GalleryImage::class, 'album_id')->where('is_published', true)->orderByDesc('created_at');
    }
}
