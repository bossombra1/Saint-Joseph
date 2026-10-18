<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use Illuminate\Http\Request;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;

class PptController extends Controller
{
    public function generate(Request $request)
    {
        $slidesData = $request->input('slides');

        // Direct form submissions do not include slides. Build them from the
        // same preview source used by the "Preview Content" button.
        if (!is_array($slidesData) || count($slidesData) === 0) {
            $previewData = $this->preview()->getData(true);
            $slidesData = $previewData['slides'] ?? [];
        }

        if (!is_array($slidesData) || count($slidesData) === 0) {
            return response()->json([
                'message' => 'Aucune intention approuvée disponible. Vérifiez les intentions enregistrées et leur statut.',
            ], 422);
        }

        foreach ($slidesData as $slideData) {
            if (!is_array($slideData) || !isset($slideData['type'])) {
                return response()->json([
                    'message' => 'Les données de l’aperçu sont invalides. Rechargez l’aperçu puis réessayez.',
                ], 422);
            }

            if (($slideData['type'] ?? null) === 'list' && !is_array($slideData['items'] ?? null)) {
                return response()->json([
                    'message' => 'Une diapositive contient une liste invalide. Rechargez l’aperçu puis réessayez.',
                ], 422);
            }
        }

        $presentation = new PhpPresentation();
        $layout = new \PhpOffice\PhpPresentation\DocumentLayout();
        $layout->setDocumentLayout(\PhpOffice\PhpPresentation\DocumentLayout::LAYOUT_SCREEN_16X9);
        $presentation->getLayout()->setDocumentLayout($layout);

        foreach ($slidesData as $s) {
            $slide = $presentation->createSlide();
            $background = new \PhpOffice\PhpPresentation\Slide\Background\Color();
            $background->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));
            $slide->setBackground($background);

            if (($s['type'] ?? '') === 'intro') {
                $slide->createLineShape(20, 20, 930, 20)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 520, 930, 520)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(20, 20, 20, 520)->getBorder()->getColor()->setARGB('FF000000');
                $slide->createLineShape(930, 20, 930, 520)->getBorder()->getColor()->setARGB('FF000000');

                $introText = $slide->createRichTextShape();
                $introText->setHeight(300)->setWidth(800)
                    ->setOffsetX($s['offsetX'] ?? 75)
                    ->setOffsetY($s['offsetY'] ?? 100);
                $introText->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $mainText = $introText->createTextRun(($s['mainText'] ?? '') . "\n");
                $mainText->getFont()->setBold(true)->setSize(36)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));

                $boldText = $introText->createTextRun(($s['boldText'] ?? '') . "\n");
                $boldText->getFont()->setBold(true)->setSize(72)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFF0000'));

                $footerText = $introText->createTextRun($s['footerText'] ?? '');
                $footerText->getFont()->setBold(true)->setSize(36)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'));
            } else {
                $headerBar = $slide->createRichTextShape();
                $headerBar->setHeight(50)->setWidth(950)->setOffsetX(0)->setOffsetY(0);
                $headerBar->setFill(
                    (new \PhpOffice\PhpPresentation\Style\Fill())
                        ->setFillType(\PhpOffice\PhpPresentation\Style\Fill::FILL_SOLID)
                        ->setStartColor(new \PhpOffice\PhpPresentation\Style\Color('FF000000'))
                );
                $headerBar->getActiveParagraph()->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $title = $headerBar->createTextRun($s['category'] ?? 'INTENTIONS');
                $title->getFont()->setBold(true)->setSize(24)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FFFFFFFF'));

                $bodyShape = $slide->createRichTextShape();
                $bodyShape->setHeight(420)->setWidth(850)
                    ->setOffsetX($s['offsetX'] ?? 50)
                    ->setOffsetY(($s['offsetY'] ?? 90) + 10);

                foreach ($s['items'] as $item) {
                    if (!is_array($item) || empty($item['name'])) {
                        continue;
                    }

                    $paragraph = $bodyShape->createParagraph();
                    $bullet = !empty($s['isRepose']) ? '+ ' : '• ';
                    $nameRun = $paragraph->createTextRun($bullet . strtoupper((string) $item['name']));
                    $nameRun->getFont()->setBold(true)->setSize(22)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF000080'));

                    if (!empty($item['description'])) {
                        $paragraph->createBreak();
                        $descriptionRun = $paragraph->createTextRun('  (' . $item['description'] . ')');
                        $descriptionRun->getFont()->setSize(16)->setItalic(true)->setColor(new \PhpOffice\PhpPresentation\Style\Color('FF666666'));
                    }
                }
            }
        }

        // PhpPresentation starts with a blank slide; remove that initial slide.
        $presentation->removeSlideByIndex(0);

        $filename = 'Mass_Intentions_' . now()->format('Y-m-d') . '.pptx';
        $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');

        return response()->streamDownload(
            static function () use ($writer): void {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    public function preview()
    {
        $today = now()->startOfDay();

        $hasIntentionsToday = MassIntention::where('status', 'approved')
            ->whereDate('preferred_date', $today)
            ->exists();

        if ($hasIntentionsToday) {
            $targetDate = $today;
        } else {
            // Prefer the next upcoming date with approved intentions.
            $nextDate = MassIntention::where('status', 'approved')
                ->whereDate('preferred_date', '>', $today)
                ->orderBy('preferred_date')
                ->value('preferred_date');

            if ($nextDate) {
                $targetDate = \Carbon\Carbon::parse($nextDate)->startOfDay();
            } else {
                // If there are no future intentions, use the latest past date.
                $lastDate = MassIntention::where('status', 'approved')
                    ->whereDate('preferred_date', '<', $today)
                    ->orderByDesc('preferred_date')
                    ->value('preferred_date');

                $targetDate = $lastDate
                    ? \Carbon\Carbon::parse($lastDate)->startOfDay()
                    : $today;
            }
        }

        $intentions = MassIntention::where('status', 'approved')
            ->whereDate('preferred_date', $targetDate)
            ->orderBy('created_at')
            ->get()
            ->groupBy('intention_type');

        $grouped = [];
        foreach ($intentions as $type => $group) {
            $typeLower = strtolower((string) $type);
            $category = 'SPECIAL INTENTIONS';

            if (str_contains($typeLower, 'thanksgiving')) {
                $category = 'THANKSGIVING';
            } elseif (str_contains($typeLower, 'repose')) {
                $category = 'ETERNAL REPOSE';
            }

            $grouped[$category] = ($grouped[$category] ?? collect())->concat($group);
        }

        $slides = [];
        foreach ($grouped as $category => $items) {
            $isRepose = $category === 'ETERNAL REPOSE';
            $isThanks = $category === 'THANKSGIVING';

            $slides[] = [
                'type' => 'intro',
                'category' => $category,
                'offsetX' => 75,
                'offsetY' => 100,
                'isRepose' => $isRepose,
                'mainText' => $isRepose
                    ? 'LASTLY, LET US PRAY FOR THE'
                    : 'LET US JOIN OUR FELLOW PARISHIONERS IN THEIR',
                'boldText' => $category,
                'footerText' => $isRepose
                    ? 'OF OUR DEPARTED LOVED ONES.'
                    : ($isThanks
                        ? 'FOR THE SPECIAL BLESSINGS AND GRACES RECEIVED FROM THE LORD.'
                        : 'IN COMMEMORATING THEIR BIRTHDAYS, WEDDING ANNIVERSARIES AND PRAY FOR THEIR SPEEDY RECOVERY.'),
            ];

            foreach ($items->chunk(12) as $chunk) {
                $slides[] = [
                    'type' => 'list',
                    'category' => $category . ($isThanks ? ' FOR THE BLESSINGS' : ''),
                    'offsetX' => 50,
                    'offsetY' => 90,
                    'isRepose' => $isRepose,
                    'items' => $chunk->map(static function ($intention) {
                        return [
                            'id' => $intention->id,
                            'name' => strtoupper((string) (
                                !empty($intention->raw_message)
                                    ? $intention->raw_message
                                    : $intention->full_name
                            )),
                            'description' => null,
                        ];
                    })->values()->toArray(),
                ];
            }
        }

        return response()->json([
            'date' => $targetDate->format('F d, Y'),
            'slides' => $slides,
        ]);
    }
}
