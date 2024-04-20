<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application_id;
    public $user;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($application_id, $user, $type = 'application')
    {
        $this->application_id = $application_id;
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->type === 'application_sent') {
            return new Envelope(
                subject: 'Application Sent',
            );
        }
        else if($this->type === 'application_approved'){
            return new Envelope(
                subject: 'Application Approved',
            );
        }
        else{
            return new Envelope(
                subject: 'Application Rejected',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->type === 'application_sent') {
            return new Content(
                view: 'mails.application_sent',
            );
        }
        else if($this->type === 'application_approved'){
            return new Content(
                view: 'mails.application_approved',
            );
        }
        else{
            return new Content(
                view: 'mails.application_rejected'
            );
        }
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
