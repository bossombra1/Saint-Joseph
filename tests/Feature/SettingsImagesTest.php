<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsImagesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Settings Admin',
            'email' => 'settings-images-admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_uploaded_leadership_photo_persists_and_uses_a_host_relative_url(): void
    {
        config(['filesystems.default' => 'local']);
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->postJson(route('admin.settings.section.update', 'leadership'), [
            'priest_name' => 'Révérend Père Test',
            'priest_image' => UploadedFile::fake()->image('priest.jpg'),
        ])->assertOk();

        $this->postJson(route('admin.settings.section.update', 'former_priests'), [
            'former_priests' => [[
                'name' => 'Ancien curé test',
                'role' => 'Curé',
                'years' => '2010–2015',
                'image' => UploadedFile::fake()->image('former-priest.jpg'),
            ]],
        ])->assertOk();

        $path = Setting::where('key', 'priest_image')->value('value');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        $formerPriest = json_decode(Setting::where('key', 'former_priests')->value('value'), true)[0];
        Storage::disk('public')->assertExists($formerPriest['image']);
        Cache::forget('global_settings');

        $settingsPage = $this->get(route('admin.settings'))->assertOk();
        preg_match('/<script type="application\/json" id="settings-previews-data">(.*?)<\/script>/s', $settingsPage->getContent(), $matches);
        $previews = json_decode($matches[1] ?? '', true);
        $this->assertSame('/storage/'.$path, $previews['priestUrl'] ?? null);
        preg_match('/<script type="application\/json" id="former-priests-data">(.*?)<\/script>/s', $settingsPage->getContent(), $formerMatches);
        $formerPriests = json_decode($formerMatches[1] ?? '', true);
        $this->assertSame('/storage/'.$formerPriest['image'], $formerPriests[0]['imageUrl'] ?? null);

        view()->share('global_settings', Setting::all()->pluck('value', 'key')->all());
        $this->get('/about')
            ->assertOk()
            ->assertSee('/storage/'.$path, false)
            ->assertSee('/storage/'.$formerPriest['image'], false);
    }
}
