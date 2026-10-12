<?php

namespace App\Notifications;

use App\Models\Inquiry;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InquirySubmitted extends Notification
{
    private $inquiry;

    public function __construct(Inquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Parish Inquiry Received - ' . $this->inquiry->reference_id)
            ->greeting('Hello ' . $this->inquiry->full_name . '!')
            ->line('We have received your inquiry regarding ' . $this->inquiry->inquiry_type . '.')
            ->line('Reference ID: ' . $this->inquiry->reference_id)
            ->line('Type: ' . $this->inquiry->inquiry_type);

        if ($this->inquiry->preferred_date) {
            $mail->line('Preferred Date: ' . $this->inquiry->preferred_date->format('M d, Y'));
        }

        $requirements = $this->getRequirements($this->inquiry->inquiry_type);
        if (!empty($requirements)) {
            $mail->line('**Initial Requirements to Prepare:**');
            foreach ($requirements as $req) {
                $mail->line('- ' . $req);
            }
        }

        $mail->line('---');
        $mail->line('**Your Submitted Details:**');
        $mail->line($this->inquiry->message);
        $mail->line('---');

        return $mail->line('Our parish team is reviewing your request and will get back to you soon.')
            ->action('Track Status', route('track.status', ['refId' => $this->inquiry->reference_id]))
            ->line('Thank you for contacting Paroisse Saint-Joseph Artisan.');
    }

    private function getRequirements($type)
    {
        $requirements = [
            'Baptism' => [
                'Birth Certificate with Registry Number (Original & Photocopy)',
                'Photocopy of Marriage Contract (If parents are married)',
                'Original Copy of Baptismal Permit (If not Riviera Bonoumin residents)',
                'Registration Fee of ₱500.00 (Fixed Amount)'
            ],
            'First Communion' => ['Photocopy of Baptismal Certificate'],
            'Confirmation' => ['Photocopy of Baptismal Certificate'],
            'Wedding' => [
                '1. Original Copy of Certificate of Baptism with "For Marriage Purpose" annotation',
                '2. Original Copy of Certificate of Confirmation with "For Marriage Purpose" annotation',
                '3. PSA-issued Birth Certificate',
                '4. PSA-issued Certificate of No Records of Marriage (Cenomar)',
                '5. Permit from the parish of the bride (if non-parishioner)',
                '6. Certificate of Legal Capacity to Marry (from Embassy, for non-Filipinos)',
                '7. Death Certificate & Marriage Contract of deceased spouse (for widows/widowers)',
                '8. Original Copy of Marriage License from respective municipalities',
                '9. NSO Certified True Copy of Civil Marriage Contract (if applicable)',
                '10. Affidavit of 5+ years cohabitation (if applicable)',
                '11. Wedding Fees to be settled one (1) week before the date'
            ],
            'Funeral Mass' => ['Photocopy of Death Certificate'],
            'Baptismal Certificate' => ['Full Name of person', 'Birthdate', 'Father & Mother Names', 'Processing Fee: ₱100.00'],
            'Confirmation Certificate' => ['Full Name', 'Date of Confirmation', 'Processing Fee'],
            'Marriage Certificate' => ['Names of Couple', 'Date of Wedding', 'Processing Fee'],
            'Car Blessing' => ['Vehicle Type & Plate Number', 'Preferred Time'],
            'House Blessing' => ['Exact Address/Location', 'Preferred Time'],
        ];

        return $requirements[$type] ?? [];
    }

    public function toArray($notifiable)
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'message' => 'New inquiry: ' . $this->inquiry->inquiry_type . ' from ' . $this->inquiry->full_name,
        ];
    }
}
