<?php

use App\Http\Controllers\AdminIntentionController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\CalendarFeedController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FacebookLiveWebhookController;
use App\Http\Controllers\GalleryAlbumController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleSlidesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\IntentionController;
use App\Http\Controllers\LiveMassController;
use App\Http\Controllers\MassScheduleController;
use App\Http\Controllers\PptController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoHighlightController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) { abort_unless(in_array($locale, ['fr','en'], true), 404); session(['locale'=>$locale]); return redirect()->back(); })->where('locale','fr|en')->name('locale.switch');

Route::get('/api/live-mass/status', [LiveMassController::class, 'status'])->name('live-mass.status');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/terms', 'terms')->name('terms');
Route::get('/mass-schedule', [MassScheduleController::class, 'index'])->name('mass-schedule');
Route::get('/mass-schedule/{id}/ical', [MassScheduleController::class, 'generateICal'])->name('mass-schedule.ical');
Route::get('/donate', [DonationController::class, 'create'])->name('donate');
Route::post('/donate/checkout', [DonationController::class, 'checkout'])->middleware('throttle:submissions')->name('donate.checkout');
Route::get('/donate/success', [DonationController::class, 'success'])->name('donate.success');
Route::get('/donate/cancel', [DonationController::class, 'cancel'])->name('donate.cancel');
Route::get('/donate/receipt/{donation}', [DonationController::class, 'receipt'])
    ->name('donation.receipt')
    ->middleware('signed');
Route::post('/paymongo/webhook', [DonationController::class, 'webhook'])->name('paymongo.webhook');

Route::get('/webhooks/facebook-live', [FacebookLiveWebhookController::class, 'verify']);
Route::post('/webhooks/facebook-live', [FacebookLiveWebhookController::class, 'handle']);

// Backward-compat: 301 redirect old /admin-portal/* bookmarks to /internal/*
Route::get('/admin-portal/{path?}', fn ($path = '') => redirect('/internal/'.$path, 301))
    ->where('path', '.*');

// Tracking Status
Route::get('/track', [TrackController::class, 'index'])->name('track');
Route::post('/track', [TrackController::class, 'track'])->name('track.post');
Route::get('/track-intention/{refId}', [TrackController::class, 'showStatus'])->name('track.status');

// Bulletins
Route::get('/bulletins', [BulletinController::class, 'index'])->name('bulletins.index');
Route::get('/bulletins/{bulletin}', [BulletinController::class, 'show'])->name('bulletins.show');
Route::get('/bulletins/{bulletin}/preview', [BulletinController::class, 'preview'])->name('bulletins.preview');
Route::get('/bulletins/{bulletin}/download', [BulletinController::class, 'download'])->name('bulletins.download');

// Announcements
Route::get('/announcements', [AnnouncementController::class, 'publicIndex'])->name('announcements.index');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'publicShow'])->name('announcements.show');
Route::get('/api/announcements/filter', [AnnouncementController::class, 'publicFilter'])->name('announcements.filter');

Route::get('/submit-intention', [IntentionController::class, 'create'])->name('submit-intention');
Route::post('/submit-intention', [IntentionController::class, 'store'])->middleware('throttle:submissions');

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry');
Route::post('/inquiry', [InquiryController::class, 'store'])->middleware('throttle:submissions')->name('inquiry.store');

Route::get('/events', [EventsController::class, 'publicIndex'])->name('events');
Route::get('/events/{event}', [EventsController::class, 'publicShow'])->name('events.show');
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/all', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{album}', [GalleryController::class, 'publicAlbum'])->name('gallery.album');

// API Proxies
Route::middleware('throttle:chat')->group(function () {
    Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');
    Route::get('/api/chatbot/poll', [ChatbotController::class, 'poll'])->name('chatbot.poll');
    Route::post('/api/chatbot/request-agent', [ChatbotController::class, 'requestAgent'])->name('chatbot.request-agent');
    Route::post('/api/chatbot/start-new-chat', [ChatbotController::class, 'startNewChat'])->name('chatbot.start-new-chat');
});

Route::get('/api/chatbot/session-status', [ChatbotController::class, 'sessionStatus'])->name('chatbot.session-status');

Route::get('/calendar.ics', [CalendarFeedController::class, 'ical'])->name('calendar.ics');

Route::get('/internal/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/internal/login', [LoginController::class, 'login'])->middleware('throttle:auth')->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'throttle:admin'])->group(function () {
    Route::get('/internal/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/internal/notifications/count', [DashboardController::class, 'getNotifications'])->name('admin.notifications.count');

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        Route::post('/internal/generate-ppt', [PptController::class, 'generate'])->name('admin.generate-ppt');
        Route::get('/internal/preview-ppt', [PptController::class, 'preview'])->name('admin.preview-ppt');
        Route::post('/internal/create-google-slides', [GoogleSlidesController::class, 'create'])->name('admin.create-google-slides');
        Route::post('/internal/live-mass/toggle', [LiveMassController::class, 'toggle'])->name('admin.live-mass.toggle');
        Route::post('/internal/live-mass/link', [LiveMassController::class, 'updateLiveLink'])->name('admin.live-mass.link');
        Route::post('/internal/live-mass/facebook-link', [LiveMassController::class, 'updateFacebookLink'])->name('admin.live-mass.facebook-link');
        Route::post('/internal/live-mass/platform', [LiveMassController::class, 'updateLivePlatform'])->name('admin.live-mass.platform');

        Route::get('/google/auth', [GoogleAuthController::class, 'auth']);
        Route::get('/google/callback', [GoogleAuthController::class, 'callback']);
    });

    // Role: super_admin or staff
    Route::middleware('role:super_admin,staff')->group(function () {
        Route::get('/internal/donations', [DonationController::class, 'adminIndex'])->name('admin.donations');
        Route::get('/internal/intentions', [AdminIntentionController::class, 'index'])->name('admin.intentions');
        Route::get('/internal/intentions/create', [AdminIntentionController::class, 'create'])->name('admin.intentions.create');
        Route::get('/internal/intentions/{id}', [AdminIntentionController::class, 'show'])->name('admin.intentions.show');
        Route::post('/internal/intentions', [AdminIntentionController::class, 'store'])->name('admin.intentions.store');
        Route::post('/internal/intentions/batch', [AdminIntentionController::class, 'batchUpdateStatus'])->name('admin.intentions.batch');
        Route::post('/internal/intentions/{id}/status', [AdminIntentionController::class, 'updateStatus'])->name('admin.intentions.status');
    });

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        // Inquiries
        Route::get('/internal/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('/internal/inquiries/{id}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::post('/internal/inquiries/{id}/accept', [InquiryController::class, 'accept'])->name('admin.inquiries.accept');
        Route::post('/internal/inquiries/{id}/decline', [InquiryController::class, 'decline'])->name('admin.inquiries.decline');
    });

    // Role: super_admin or soccom
    Route::middleware('role:super_admin,soccom')->group(function () {
        Route::resource('/internal/schedules', ScheduleController::class)->names('admin.schedules');
        Route::resource('/internal/announcements', AnnouncementController::class)->names('admin.announcements');
        Route::resource('/internal/events', EventsController::class)->names('admin.events');
        Route::resource('/internal/gallery', GalleryAlbumController::class)->names('admin.gallery');
        Route::post('/internal/gallery/{album}/add-images', [GalleryAlbumController::class, 'addImages'])->name('admin.gallery.add-images');
        Route::delete('/internal/gallery/image/{image}', [GalleryAlbumController::class, 'removeImage'])->name('admin.gallery.remove-image');

        // Video Highlights (Standalone)
        Route::resource('/internal/highlights', VideoHighlightController::class)->names('admin.highlights');
        Route::post('/internal/highlights/reorder', [VideoHighlightController::class, 'reorder'])->name('admin.highlights.reorder');

        Route::get('/internal/bulletins', [BulletinController::class, 'adminIndex'])->name('admin.bulletins.index');
        Route::post('/internal/bulletins', [BulletinController::class, 'store'])->name('admin.bulletins.store');
        Route::get('/internal/bulletins/{bulletin}/preview', [BulletinController::class, 'adminPreview'])->name('admin.bulletins.preview');
        Route::get('/internal/bulletins/{bulletin}/file', [BulletinController::class, 'preview'])->name('admin.bulletins.file');
        Route::delete('/internal/bulletins/{bulletin}', [BulletinController::class, 'destroy'])->name('admin.bulletins.destroy');

        // Live Chat Admin
        Route::get('/internal/chats', [ChatbotController::class, 'adminIndex'])->name('admin.chats.index');
        Route::get('/internal/chats/sessions-html', [ChatbotController::class, 'adminSessionsHtml'])->name('admin.chats.sessions-html');
        Route::get('/internal/chats/{id}', [ChatbotController::class, 'adminShow'])->name('admin.chats.show');
        Route::post('/internal/chats/{id}/reply', [ChatbotController::class, 'adminReply'])->name('admin.chats.reply');
        Route::post('/internal/chats/{id}/resolve', [ChatbotController::class, 'resolve'])->name('admin.chats.resolve');
        Route::post('/internal/chats/{id}/pause', [ChatbotController::class, 'pause'])->name('admin.chats.pause');
        Route::post('/internal/chats/{id}/resume', [ChatbotController::class, 'resume'])->name('admin.chats.resume');
        Route::get('/internal/chats/{id}/poll', [ChatbotController::class, 'adminPoll'])->name('admin.chats.poll');
        Route::post('/internal/chats/{id}/typing', [ChatbotController::class, 'adminTyping'])->name('admin.chats.typing');
    });

    // Role: super_admin only
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/internal/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/internal/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/internal/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/internal/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/internal/logs', [DashboardController::class, 'logs'])->name('admin.logs');
        Route::get('/internal/settings', [SettingController::class, 'index'])->name('admin.settings');
        Route::post('/internal/settings/{section}', [SettingController::class, 'updateSection'])->name('admin.settings.section.update');
    });
});
