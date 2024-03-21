<?php

namespace App\Mail;

use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class LoginAttemptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(private $mail)
    {
        $token = Str::random(50);
        $reset = Str::random(32);

        $passReset = PasswordReset::updateOrCreate(
            ['email' => $mail],
            [
                'token' => $token,
                'reset_token' => $reset
            ]
        );

        // TODO: Change it to front login
        $this->resetUrl = url('/reset', ['token' => $passReset->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address("GCommerce@gigant.tech", "GCommerce"),
            subject: 'Login Attempt Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.login.attempted',
            with: [
                'url' => $this->resetUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
