<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Client;
use App\Data;

class AlertICEContacts extends Mailable
{
    use Queueable, SerializesModels;
    
    public $client;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client, Data $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.alert_ice');
    }
}
