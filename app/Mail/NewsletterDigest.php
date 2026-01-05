<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterDigest extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public array $item;
    public string $typeLabel;

    public function __construct(
        string $subjectLine,
        array $item,
        string $typeLabel
    ) {
        $this->subjectLine = $subjectLine;
        $this->item = $item;
        $this->typeLabel = $typeLabel;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.newsletter-digest')
            ->with([
                'item' => $this->item,
                'typeLabel' => $this->typeLabel,
            ]);
    }
}
