<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCompanyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $directors;

    /**
     * Create a new message instance.
     *
     * @param $company
     * @param $directors
     */
    public function __construct($company, $directors)
    {
        $this->company = $company;
        $this->directors = $directors;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("New Active Company: {$this->company['title']}")
            ->view('emails.new_company')  // Reference the email view
            ->with([
                'company' => $this->company,
                'directors' => $this->directors,
            ]);
    }
}
