<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WindowCleaningAdminNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Ny bokning: Fönsterputsning #' . $this->booking->id)
            ->view('emails.window-cleaning-admin');
    }
}