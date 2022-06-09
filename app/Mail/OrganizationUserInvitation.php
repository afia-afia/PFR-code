<?php

namespace App\Mail;

use App\Organization;
use App\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizationUserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    private $organization;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, User $user)
    {
        $this->organization = $organization;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.organization.invitation', [
            "organization" => $this->organization,
            "user" => $this->user
        ])->subject("Join " . $this->organization->name);
    }
}
