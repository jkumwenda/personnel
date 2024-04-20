<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExamMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $exam;
    public $exam_number;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $exam, $exam_number = '', $type = 'exam')
    {
        $this->user = $user;
        $this->exam = $exam;
        $this->exam_number = $exam_number;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if($this->type === 'exam_register'){
            return new Envelope(
                subject: 'Exam Registration',
            );
        }
        else if($this->type === 'exam_approval'){
            return new Envelope(
                subject: $this->exam->exam_name.' Personnel Registration Exams',
            );
        }
        else if($this->type === 'exam_dg_approve'){
            return new Envelope(
                subject: $this->exam->exam_name.' Personnel Registration Exams',
            );
        }
        else if($this->type === 'exam_publish'){
            return new Envelope(
                subject: $this->exam->exam_name.' Personnel Registration Exams',
            );
        }
        else{
            return new Envelope(
                subject: 'Exam Notification',
            );
        }

    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if($this->type === 'exam_register'){
            return new Content(
                view: 'mails.exam_register',
            );
        }
        else if($this->type === 'exam_approval'){
            return new Content(
                view: 'mails.exam_approval',
            );
        }
        else if($this->type === 'exam_dg_approve'){
            return new Content(
                view: 'mails.exam_dg_approve',
            );
        }
        else if($this->type === 'exam_publish'){
            return new Content(
                view: 'mails.exam_results',
            );
        }
        else{
            return new Content(
                view: 'mails',
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
