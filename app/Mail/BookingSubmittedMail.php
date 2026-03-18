<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {
    }

    public function build(): self
    {
        $timeFrom = $this->booking->time_from ? substr((string) $this->booking->time_from, 0, 5) : '—';
        $timeTo = $this->booking->time_to ? substr((string) $this->booking->time_to, 0, 5) : '—';

        return $this
            ->subject('Ny bokning: ' . ($this->booking->customer_name ?? 'Kund'))
            ->view('emails.booking-submitted')
            ->with([
                'booking' => $this->booking,
                'maskedPersonnummer' => $this->booking->personnummer_last4
                    ? '******-' . $this->booking->personnummer_last4
                    : '—',
                'bookingDateFormatted' => $this->booking->booking_date?->format('Y-m-d') ?? '—',
                'timeLabel' => $timeFrom . ' - ' . $timeTo,
            ]);
    }
}