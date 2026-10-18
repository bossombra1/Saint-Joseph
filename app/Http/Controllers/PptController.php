<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PptController extends Controller
{
    /**
     * Return the approved mass intentions used by the Word preview.
     */
    public function preview(Request $request)
    {
        $today = now()->startOfDay();
        $selectedIds = array_values(array_filter((array) $request->query('ids', []), 'is_string'));

        $query = MassIntention::query()->whereNotNull('preferred_date');

        if ($selectedIds !== []) {
            // An explicit selection always means exactly those rows, including past dates.
            $query->whereIn('id', $selectedIds);
        } else {
            // With no selection, include approved intentions from today onward only.
            $query->where('status', 'approved')
                ->whereDate('preferred_date', '>=', $today);
        }

        $records = $query->orderBy('preferred_date')
            ->orderBy('mass_time')
            ->orderBy('created_at')
            ->get();

        $sections = [];

        foreach ($records as $intention) {
            $dateLabel = $intention->preferred_date
                ? Carbon::parse($intention->preferred_date)->format('d/m/Y')
                : 'Date non précisée';

            $typeLower = mb_strtolower((string) $intention->intention_type);

            if (
                str_contains($typeLower, 'thanksgiving')
                || str_contains($typeLower, 'action de grâce')
                || str_contains($typeLower, 'action de grace')
            ) {
                $category = 'ACTIONS DE GRÂCE';
            } elseif (
                str_contains($typeLower, 'repose')
                || str_contains($typeLower, 'défunt')
                || str_contains($typeLower, 'defunt')
            ) {
                $category = 'PRIÈRES POUR LES DÉFUNTS';
            } else {
                $category = 'INTENTIONS SPÉCIALES';
            }

            $sectionKey = $dateLabel . '|' . $category;
            if (!isset($sections[$sectionKey])) {
                $sections[$sectionKey] = [
                    'category' => $dateLabel . ' — ' . $category,
                    'introduction' => '',
                    'items' => [],
                ];
            }

            $sections[$sectionKey]['items'][] = [
                'name' => (string) (
                    !empty($intention->raw_message)
                        ? $intention->raw_message
                        : $intention->full_name
                ),
                'type' => (string) $intention->intention_type,
                'time' => (string) ($intention->mass_time ?? ''),
            ];
        }

        return response()->json([
            'date' => $selectedIds !== []
                ? 'Intentions sélectionnées'
                : 'À partir du ' . $today->format('d/m/Y'),
            'sections' => array_values($sections),
        ]);
    }

    /**
     * Generate a standard editable DOCX without relying on Composer's autoloader
     * or PHPWord. A DOCX file is an Office Open XML package stored as a ZIP.
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'date' => ['nullable', 'string', 'max:30'],
            'sections' => ['nullable', 'array'],
            'sections.*.category' => ['required_with:sections', 'string', 'max:150'],
            'sections.*.introduction' => ['nullable', 'string', 'max:3000'],
            'sections.*.items' => ['required_with:sections', 'array'],
            'sections.*.items.*.name' => ['required', 'string', 'max:1000'],
            'sections.*.items.*.type' => ['nullable', 'string', 'max:150'],
            'sections.*.items.*.time' => ['nullable', 'string', 'max:100'],
        ]);

        $sections = $data['sections'] ?? [];
        $dateLabel = $data['date'] ?? now()->format('d/m/Y');

        if (empty($sections)) {
            $preview = $this->preview()->getData(true);
            $sections = $preview['sections'] ?? [];
            $dateLabel = $data['date'] ?? ($preview['date'] ?? $dateLabel);
        }

        if (empty($sections)) {
            return response()->json([
                'message' => 'Aucune intention approuvée disponible pour créer le document Word.',
            ], 422);
        }

        if (!class_exists(\ZipArchive::class)) {
            return response()->json([
                'message' => 'L’extension PHP ZIP (ZipArchive) est absente. Active ext-zip dans PHP pour générer le fichier Word.',
            ], 500);
        }

        $paragraphs = [];
        $paragraphs[] = $this->wordParagraph('SAINT-JOSEPH ARTISAN', 'Subtitle', true, 12, '666666', 'center');
        $paragraphs[] = $this->wordParagraph('INTENTIONS DE MESSE', 'Title', true, 20, '17322D', 'center');
        $paragraphs[] = $this->wordParagraph('Date : ' . $dateLabel, null, true, 12, '000000', 'center');
        $paragraphs[] = $this->wordParagraph('');

        foreach ($sections as $section) {
            $category = trim((string) ($section['category'] ?? 'INTENTIONS'));
            $paragraphs[] = $this->wordParagraph($category, 'Heading1', true, 15, '17322D');

            $introduction = trim((string) ($section['introduction'] ?? ''));
            if ($introduction !== '') {
                $paragraphs[] = $this->wordParagraph($introduction);
            }

            foreach (($section['items'] ?? []) as $item) {
                $name = trim((string) ($item['name'] ?? ''));
                if ($name === '') {
                    continue;
                }

                $paragraphs[] = $this->wordParagraph('• ' . mb_strtoupper($name), null, true, 11, '000000');

                $details = array_values(array_filter([
                    trim((string) ($item['type'] ?? '')),
                    trim((string) ($item['time'] ?? '')),
                ], static fn ($value) => $value !== ''));

                if ($details !== []) {
                    $paragraphs[] = $this->wordParagraph('   ' . implode(' — ', $details), null, false, 9, '666666');
                }
            }

            $paragraphs[] = $this->wordParagraph('');
        }

        $paragraphs[] = $this->wordParagraph('Document préparé par l’administration paroissiale.', null, false, 9, '777777');

        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            . '<w:body>' . implode('', $paragraphs)
            . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/>'
            . '<w:pgMar w:top="1200" w:right="1400" w:bottom="1200" w:left="1400" w:header="708" w:footer="708" w:gutter="0"/>'
            . '</w:sectPr></w:body></w:document>';

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
            . '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>'
            . '</Types>';

        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
            . '</Relationships>';

        $documentRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            . '<w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/>'
            . '<w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/><w:sz w:val="22"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/>'
            . '<w:pPr><w:jc w:val="center"/><w:spacing w:after="240"/></w:pPr>'
            . '<w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/><w:b/><w:color w:val="17322D"/><w:sz w:val="40"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/>'
            . '<w:pPr><w:jc w:val="center"/></w:pPr><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/></w:rPr></w:style>'
            . '<w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/>'
            . '<w:pPr><w:keepNext/><w:spacing w:before="240" w:after="120"/></w:pPr>'
            . '<w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/><w:b/><w:color w:val="17322D"/><w:sz w:val="30"/></w:rPr></w:style>'
            . '</w:styles>';

        $temporaryFile = tempnam(sys_get_temp_dir(), 'mass-intentions-');
        if ($temporaryFile === false) {
            return response()->json([
                'message' => 'Impossible de créer le fichier Word temporaire.',
            ], 500);
        }

        $zip = new ZipArchive();
        $opened = $zip->open($temporaryFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($opened !== true) {
            @unlink($temporaryFile);
            return response()->json([
                'message' => 'Impossible de préparer le fichier Word (ZIP).',
            ], 500);
        }

        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rootRels);
        $zip->addFromString('word/document.xml', $documentXml);
        $zip->addFromString('word/_rels/document.xml.rels', $documentRels);
        $zip->addFromString('word/styles.xml', $stylesXml);
        $zip->close();

        $filename = 'Intentions_de_messe_' . now()->format('Y-m-d') . '.docx';

        return response()->streamDownload(
            static function () use ($temporaryFile): void {
                readfile($temporaryFile);
                @unlink($temporaryFile);
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]
        );
    }

    /**
     * Build a safe WordprocessingML paragraph.
     */
    private function wordParagraph(
        string $text,
        ?string $style = null,
        bool $bold = false,
        int $fontSize = 11,
        string $color = '000000',
        ?string $alignment = null
    ): string {
        $pPr = '';
        if ($style !== null) {
            $pPr .= '<w:pStyle w:val="' . $style . '"/>';
        }
        if ($alignment !== null) {
            $pPr .= '<w:jc w:val="' . $alignment . '"/>';
        }
        if ($pPr !== '') {
            $pPr = '<w:pPr>' . $pPr . '</w:pPr>';
        }

        $runProperties = '<w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial"/>';
        if ($bold) {
            $runProperties .= '<w:b/>';
        }
        $runProperties .= '<w:color w:val="' . $color . '"/><w:sz w:val="' . ($fontSize * 2) . '"/></w:rPr>';

        $escaped = htmlspecialchars($text, ENT_XML1 | ENT_COMPAT, 'UTF-8');

        return '<w:p>' . $pPr . '<w:r>' . $runProperties
            . '<w:t xml:space="preserve">' . $escaped . '</w:t>'
            . '</w:r></w:p>';
    }
}
