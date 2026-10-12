<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\MassSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageUpcomingContentTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_homepage_shows_the_next_mass_and_only_upcoming_events_in_french(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-11 10:30:00', 'Asia/Manila'));

        MassSchedule::create([
            'title' => 'Sunday Mass',
            'mass_type' => 'sunday',
            'day_of_week' => ['Sunday'],
            'time' => ['9:30 AM', '11:30 AM'],
            'is_active' => true,
        ]);

        Event::create([
            'title' => 'Événement terminé',
            'event_date' => '2026-10-11',
            'event_time' => [['time' => '9:00 AM']],
            'is_published' => true,
        ]);

        Event::create([
            'title' => 'Événement à venir',
            'event_date' => '2026-10-11',
            'event_time' => [['time' => '11:45 AM']],
            'is_published' => true,
        ]);

        $response = $this->withoutExceptionHandling()->get('/');
        $content = $response->getContent();

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('11:30', $content, 'The next mass time should be shown.');
        $this->assertStringNotContainsString('9:30', $content, 'The elapsed mass time should not be shown.');
        preg_match('/<div class="event-badge-today">[\s\S]*?<span[^>]*>([^<]+)<\/span>/', $content, $dayLabel);
        $this->assertSame('Aujourd’hui', $dayLabel[1] ?? null, 'The mass day should be localized.');
        $this->assertStringContainsString('ÉVÉNEMENTS À VENIR', $content, 'The events heading should be localized.');
        $this->assertStringContainsString('ÉVÉNEMENT À VENIR', $content, 'The future event should be shown.');
        $this->assertStringNotContainsString('Événement terminé', $content, 'The elapsed event should not be shown.');
        $this->assertStringContainsString('11 octobre 2026', $content, 'The event date should be localized.');
        $this->assertStringContainsString('window.location.reload()', $content, 'The page should refresh at the next schedule transition.');
    }

    public function test_homepage_includes_one_off_mass_schedules(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-11 10:30:00', 'Asia/Manila'));

        MassSchedule::create([
            'title' => 'Messe spéciale',
            'mass_type' => 'weekday',
            'specific_date' => '2026-10-12',
            'time' => ['6:45 AM'],
            'is_active' => true,
        ]);

        $response = $this->withoutExceptionHandling()->get('/');
        $content = $response->getContent();

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('MESSE SPÉCIALE', $content, 'The one-off mass should be selected.');
        $this->assertStringContainsString('Demain', $content, 'The one-off mass date should be localized.');
        $this->assertStringContainsString('06:45', $content, 'The one-off mass time should use French formatting.');
    }

    public function test_homepage_uses_english_when_selected(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-12 10:30:00', 'Asia/Manila'));

        MassSchedule::create([
            'title' => 'Weekday Mass',
            'mass_type' => 'weekday',
            'day_of_week' => ['Monday'],
            'time' => ['11:30 AM'],
            'is_active' => true,
        ]);

        $response = $this->withSession(['locale' => 'en'])->withoutExceptionHandling()->get('/');
        $content = $response->getContent();

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('Today', $content);
        $this->assertStringContainsString('11:30 AM', $content);
        $this->assertStringContainsString('UPCOMING EVENTS', $content);
        $this->assertStringContainsString("What's Happening", $content);
        $this->assertStringContainsString('NEXT MASS', $content);
    }
}
