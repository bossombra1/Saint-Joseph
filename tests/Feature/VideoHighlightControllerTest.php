<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VideoHighlight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoHighlightControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'video-highlight-admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_direct_video_upload_is_publicly_playable_even_without_gallery_albums(): void
    {
        config(['filesystems.default' => 'local']);
        Storage::fake('local');
        Cache::put('gallery_highlights', collect(), 600);
        $video = UploadedFile::fake()->create('parish-highlight.mp4', 512, 'video/mp4');

        $this->actingAs($this->admin())
            ->post(route('admin.highlights.store'), [
                'title' => 'Parish Celebration',
                'description' => 'A short test clip.',
                'video_file' => $video,
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.highlights.index'))
            ->assertSessionHasNoErrors();

        $highlight = VideoHighlight::firstOrFail();
        Storage::disk('local')->assertExists('highlights/'.$highlight->getRawOriginal('video_path'));
        $this->assertStringContainsString('signature=', $highlight->video_url);
        $this->get($highlight->video_url)->assertOk();
        $this->assertFalse(Cache::has('gallery_highlights'));

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Parish Celebration')
            ->assertSee('video/mp4')
            ->assertSee('highlights/'.$highlight->getRawOriginal('video_path'), false);
    }

    public function test_youtube_watch_links_use_the_embeddable_url_on_the_public_gallery(): void
    {
        VideoHighlight::create([
            'title' => 'Sunday Mass',
            'video_path' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published' => true,
            'sort_order' => 0,
        ]);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ', false)
            ->assertDontSee('https://www.youtube.com/watch?v=dQw4w9WgXcQ', false);
    }
}