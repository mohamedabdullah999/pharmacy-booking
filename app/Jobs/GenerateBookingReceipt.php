<?php

namespace App\Jobs;

use App\Models\Booking;
use Mpdf\Mpdf; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class GenerateBookingReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle()
    {
        $fileName = 'receipt_' . $this->booking->reference_number . '.pdf';
        $filePath = 'receipts/' . $fileName;

        if (Storage::disk('public')->exists($filePath)) {
            return;
        }

        $this->booking->load(['item', 'pricingRule']);

        $data = [
            'booking' => $this->booking,
            'logo_path' => public_path('images/zu-logo.jpeg')
        ];

        $html = View::make('pdf.receipt', $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);
        
        $pdfContent = $mpdf->Output('', 'S');
        Storage::disk('public')->put($filePath, $pdfContent);
    }
}