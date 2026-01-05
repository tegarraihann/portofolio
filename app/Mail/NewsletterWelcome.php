<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $ctaUrl;
    public string $ctaLabel;

    public function __construct(string $subjectLine, string $ctaUrl, string $ctaLabel)
    {
        $this->subjectLine = $subjectLine;
        $this->ctaUrl = $ctaUrl;
        $this->ctaLabel = $ctaLabel;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.newsletter-welcome')
            ->with([
                'ctaUrl' => $this->ctaUrl,
                'ctaLabel' => $this->ctaLabel,
            ]);
    }
}
