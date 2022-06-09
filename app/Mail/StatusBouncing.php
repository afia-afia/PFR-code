<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class StatusBouncing extends Mailable
{
    use Queueable, SerializesModels;

    private $change;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\StatusChange $change)
    {
        $this->change = $change;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.server.bouncing')
                ->with(["change" => $this->change])
                ->from("monitor@web-d.be")
                ->subject(
                    $this->change->server()->organization->name . " / "
                    . $this->change->server()->name . " : Status bouncing"
                );
    }
}
