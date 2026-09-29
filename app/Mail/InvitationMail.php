<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

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
            subject: 'Undangan '.config('invitation.event_name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
            with: [
                'eventName' => config('invitation.event_name'),
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
