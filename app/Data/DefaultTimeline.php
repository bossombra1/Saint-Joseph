<?php

namespace App\Data;

class DefaultTimeline
{
    public static function entries(): array
    {
        return [
            [
                'year'  => '1982',
                'badge' => '',
                'title' => 'The image is carved',
                'short' => 'Carved in Paete, Laguna through funds gathered by Mrs. Delia Sanchez and Mrs. Fely Canta.',
                'full'  => 'Blessed by Rev. Fr. Rey Amante, the image was first housed at the Canta residence, then transferred in procession to the make-shift chapel.',
            ],
            [
                'year'  => '2010',
                'badge' => '',
                'title' => 'Canonical erection',
                'short' => 'The parish was canonically erected on October 16, 2010.',
                'full'  => 'On the same day, the Queen of the Most Holy Rosary of Pacita was officially declared patroness of the parish community.',
            ],
            [
                'year'  => '1986',
                'badge' => '',
                'title' => 'Church dedication',
                'short' => 'The Paroisse Saint-Joseph Artisan Church was blessed and dedicated on December 6.',
                'full'  => 'Jointly officiated by Msgr. Bruno Torpigliani (Papal Nuncio), Bishop Pedro Bantigue, and Auxiliary Bishop Gabriel Reyes.',
            ],
            [
                'year'  => '2009',
                'badge' => '',
                'title' => 'Our Lady of Pacita',
                'short' => "Rev. Fr. Mario P. Rivera began promoting the endearing title 'Our Lady of Pacita.'",
                'full'  => "This title integrated the community's deep sense of belonging with the Blessed Mother.",
            ],
            [
                'year'  => '2021',
                'badge' => '',
                'title' => 'Hermandad established',
                'short' => 'The Hermandad del Santo Rosario — the Rosary Confraternity of Pacita — was formally established.',
                'full'  => 'Established to propagate devotion to Our Lady. The Perpetual Novena is held every Saturday.',
            ],
            [
                'year'  => '2024',
                'badge' => 'Cultural Heritage',
                'title' => 'Important Cultural Property',
                'short' => 'The image was declared an Important Cultural Property of the City of San Pedro.',
                'full'  => 'Via Sangguniang Panlungsod Resolution No. 2024-198, adopted October 1, 2024.',
            ],
            [
                'year'  => '2025',
                'badge' => 'Royal Honor',
                'title' => 'Queen of the City',
                'short' => "Our Lady was accorded the honorific title 'Queen of the City of San Pedro.'",
                'full'  => 'Via Sangguniang Panlungsod Resolution No. 2025-93, adopted June 10, 2025.',
            ],
        ];
    }
}
