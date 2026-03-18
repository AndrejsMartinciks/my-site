<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $lead;

    public function __construct(array $lead)
    {
        $this->lead = $lead;
    }

    public function build(): self
    {
        return $this
            ->subject('Ny lead från webbplatsen - Clean Source AB')
            ->view('emails.lead-submitted');
    }
}