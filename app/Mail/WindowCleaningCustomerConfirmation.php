<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WindowCleaningCustomerConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Bekräftelse på din bokningsförfrågan')
            ->view('emails.window-cleaning-customer');
    }
}