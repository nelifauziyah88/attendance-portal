<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Carbon;

class InvitationMail extends Mailable
{
    public function __construct(
        public readonly Invitation $invitation,
        public readonly string $invitationUrl,
        public readonly string $qrCodePng,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan '.$this->invitation->event->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
            with: [
                'event' => $this->invitation->event,
                'eventDate' => Carbon::parse($this->invitation->event->event_date)->locale('id')->translatedFormat('l, j F Y'),
                'eventTime' => substr($this->invitation->event->start_time, 0, 5)
                    .($this->invitation->event->end_time ? ' - '.substr($this->invitation->event->end_time, 0, 5) : '').' WIB',
                'participant' => $this->invitation->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrCodePng, 'qr-'.$this->invitation->user->badge_id.'.png')
                ->withMime('image/png'),
        ];
    }
}
