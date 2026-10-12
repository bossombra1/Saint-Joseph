<?php

namespace Tests\Feature;

use App\Models\Bulletin;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BulletinControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'bulletins-admin@example.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    private function pdfWithPages(int $pageCount): string
    {
        $pages = '';
        for ($page = 1; $page <= $pageCount; $page++) {
            $pageBreak = $page < $pageCount ? 'page-break-after: always;' : '';
            $pages .= '<div style="height: 700px; '.$pageBreak.'">Bulletin page '.$page.'</div>';
        }

        return Pdf::loadHTML($pages)->output();
    }

    public function test_admin_can_upload_a_pdf_for_public_reading_and_download(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $pdf = UploadedFile::fake()->createWithContent('parish-bulletin.pdf', $this->pdfWithPages(5));

        $this->get(route('admin.bulletins.index'))
            ->assertOk()
            ->assertSee('<label for="bulletin-title"', false)
            ->assertSee('<label for="bulletin-published-date"', false)
            ->assertSee('<label for="bulletin-file"', false);

        $this->post(route('admin.bulletins.store'), [
            'title' => 'Bulletin du dimanche',
            'published_date' => '2026-10-11',
            'file' => $pdf,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $bulletin = Bulletin::firstOrFail();
        Storage::disk('public')->assertExists($bulletin->file_path);
        $this->get(route('admin.bulletins.index'))
            ->assertOk()
            ->assertSee(route('admin.bulletins.preview', $bulletin));

        $this->get(route('admin.bulletins.preview', $bulletin))
            ->assertOk()
            ->assertSee($bulletin->title)
            ->assertSee(route('admin.bulletins.file', $bulletin));

        $adminPdf = $this->get(route('admin.bulletins.file', $bulletin))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $adminPdf->streamedContent());

        $this->get(route('bulletins.index'))
            ->assertOk()
            ->assertSee('Bulletin du dimanche')
            ->assertSee(route('bulletins.show', $bulletin));

        $this->get(route('bulletins.show', $bulletin))
            ->assertOk()
            ->assertSee('data-bulletin-reader', false)
            ->assertSee(route('bulletins.preview', $bulletin));

        $preview = $this->get(route('bulletins.preview', $bulletin))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $preview->streamedContent());

        $this->get(route('bulletins.download', $bulletin))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename="Bulletin du dimanche.pdf"');
    }

    public function test_admin_can_upload_a_pdf_with_fewer_than_five_pages(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $pdf = UploadedFile::fake()->createWithContent('short-bulletin.pdf', $this->pdfWithPages(4));

        $this->from(route('admin.bulletins.index'))
            ->post(route('admin.bulletins.store'), [
                'title' => 'Bulletin trop court',
                'published_date' => '2026-10-11',
                'file' => $pdf,
            ])
            ->assertRedirect(route('admin.bulletins.index'))
            ->assertSessionHasNoErrors();

        $bulletin = Bulletin::firstOrFail();
        $this->assertSame('Bulletin trop court', $bulletin->title);
        Storage::disk('public')->assertExists($bulletin->file_path);
    }
}