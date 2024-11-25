<?php

namespace App\Mail;

use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Foreach_;

class SendFormattedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $template_id,  $data, $mail_subject, $mail_content, $attatchment;
    public function __construct($template_id, $data = [], $attatchment = [])
    {
        $this->template_id = $template_id;
        $this->attatchment = $attatchment;
        $this->data = $data;
        $mailData = MailTemplate::findOrFail($this->template_id);
        $keywords = explode(',', ($mailData->keywords ?? '')) ?? [];
        $this->mail_subject = str_replace($keywords, $data, $mailData->subject ?? '');
        $this->mail_content = str_replace($keywords, $data, $mailData->content ?? '');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mail_subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.test',
            with: [
                'content' => $this->mail_content
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
        $returnData = [];
        foreach ($this->attatchment as $key => $value) {
            if(file_exists(public_path($value))){
                $returnData[] = asset($value);
            }else{
                Log::warning("Attatchment path not found: ".public_path($value));
            }
        }
        return $returnData;
    }
}
