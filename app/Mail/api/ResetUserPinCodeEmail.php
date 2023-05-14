<?php

namespace App\Mail\api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetUserPinCodeEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $token =null,$user_name =null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $token,$user_name)
    {
        //
        $this->token =  $token;
        $this->user_name =  $user_name;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reset User Pin Code Email',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'pincode.sendMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->view('pincode.sendMail', ['token' => $this->token , 'user_name' => $this->user_name]);
    }

}
