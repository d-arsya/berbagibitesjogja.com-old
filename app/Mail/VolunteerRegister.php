<?php

namespace App\Mail;

use App\Models\Volunteer\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $email;

    private $name;

    private $division;

    private $role;

    private $password;

    public function __construct(User $user, $password)
    {
        $this->email = $user->email;
        $this->role = $user->role;
        $this->name = $user->name;
        $this->division = $user->division()->name;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Volunteer Register',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.volunteer.register',
            with: [
                'email' => $this->email,
                'password' => $this->password,
                'name' => $this->name,
                'role' => $this->role,
                'division' => $this->division,
            ]
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
